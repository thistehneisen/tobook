<?php namespace App\Appointment\Models;

use Config, Util;
use Carbon\Carbon;
use App\Core\Models\Base;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Slot\Strategy;
use App\Appointment\Models\Slot\Context;
use App\Appointment\Models\Slot\Backend;
use App\Appointment\Models\Slot\NewBackend;
use App\Appointment\Models\Slot\Frontend;

class Employee extends Base
{
    protected $table = 'as_employees';

    private $plustime = [];
    public $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'description',
        'is_subscribed_email',
        'is_subscribed_sms',
        'is_active'
    ];

    protected $rulesets = [
        'saving' => [
            'name'  => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]
    ];

    /**
     * These variables to use as a dictionary to easy to get back
     *  and limit access to db in for loop
     */
    private $bookedSlot     = [];
    private $freetimeSlot   = [];
    private $customTimeSlot = [];
    private $strategy       = [];

    public function setBookedSlot(array $data)
    {
        $this->bookedSlot = $data;
    }

    public function setFreetimeSlot(array $data)
    {
        $this->freetimeSlot = $data;
    }

    public function setCustomTimeSlot(array $data)
    {
        $this->customTimeSlot = $data;
    }

    public function getDefaultTimes()
    {
        $defaultTimes = $this->defaultTimes;
        if ($defaultTimes->isEmpty()) {
            $defaultTimeConfig = Config::get('employee.default_time');
            $data = [];
            foreach ($defaultTimeConfig as $time) {
                $obj= (object) $time;
                $obj->default = true;
                $data[] = $obj;
            }
            $defaultTimes = new \Illuminate\Support\Collection($data);
        }

        return $defaultTimes;
    }

    public function getDefaulTimesByDay($day)
    {
        $defaultTimes = $this->getDefaultTimes();

        return $defaultTimes->filter(function ($item) use ($day) {
            return $item->type === $day;
        });
    }

    public function getDefaulTimesByDayOfWeek($dayOfWeek)
    {
        $day = Util::getDayOfWeekText($dayOfWeek);
        return $this->getDefaulTimesByDay($day)->first();
    }

    public function getDefaultWorkingTimes()
    {
        $defaultTimes = $this->getDefaultTimes();
        $startTimes   = [];
        $endTimes     = [];

        foreach ($defaultTimes as $time) {
            if ($time->is_day_off === 0) {
                $startTimes[] = Carbon::createFromFormat('H:i:s', $time->start_at);
                $endTimes[] =  Carbon::createFromFormat('H:i:s', $time->end_at);
            }
        }

        //low to high
        usort($startTimes, function($a, $b){
            if ($a === $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });

        //high to low
        usort($endTimes, function($a, $b){
            if ($a === $b) {
                return 0;
            }
            return ($a > $b) ? -1 : 1;
        });

        $workingTimes = [];
        $startHour   = (int)isset($startTimes[0]) ? $startTimes[0]->hour : 7;
        $startMinute = (int)isset($startTimes[0]) ? $startTimes[0]->minute: 0;
        $endHour     = (int)isset($endTimes[0]) ? $endTimes[0]->hour : 21;
        $endMinute   = (int)isset($endTimes[0]) ? $endTimes[0]->minute : 0;

        $endHour   = ($endMinute == 0) ? $endHour - 1 : $endHour;
        $endMinute = ($endMinute == 0) ? 45 : $endMinute;
        for ($i = $startHour; $i<= $endHour - 1; $i++) {
            if ($i === $startHour) {
                $workingTimes[$i] = range($startMinute, 45, 15);
            }
            if ($i !== $startHour && $i !== $endHour)
            {
                $workingTimes[$i] = range(0, 45, 15);
            }
            if ($i === $endHour) {
                 $workingTimes[$i] = range(0, $endMinute, 15);
            }
        }
        return $workingTimes;
    }

    public function getTodayDefaultStartAt($weekday = null)
    {
        if ($weekday === null) {
            $weekday = Carbon::now()->dayOfWeek;
        }
        $dayOfWeek = Util::getDayOfWeekText($weekday);
        $todayStartAt = $this->getDefaulTimesByDay($dayOfWeek)->first()->start_at;

        return Carbon::createFromFormat('H:i:s', $todayStartAt);
    }

    public function getTodayDefaultEndAt($weekday = null)
    {
        if ($weekday === null) {
            $weekday = Carbon::now()->dayOfWeek;
        }
        $dayOfWeek = Util::getDayOfWeekText($weekday);
        $todayEndAt = $this->getDefaulTimesByDay($dayOfWeek)->first()->end_at;
        return Carbon::createFromFormat('H:i:s', $todayEndAt);
    }

    //TODO change to another method to compare time
    public function getSlotClass($date, $hour, $minute, $context = 'backend', $service = null)
    {
        //Cache by date for employee view
        if(empty($this->strategy[$date])){
            $this->strategy[$date] = new NewBackend();
            if ($context === 'frontend') {
                $this->strategy[$date] = new FrontEnd();
            }
        }
        $context = new Context($this->strategy[$date]);

        return $context->determineClass($date, $hour, $minute, $this, $service);
    }

    public function getBooked($date, $hour, $minute)
    {
        if (!empty($this->bookedSlot[$date][$hour][$minute])) {
            return $this->bookedSlot[$date][$hour][$minute];
        }

        return null;
    }

    public function getFreetime($date, $hour, $minute)
    {
        if (!empty($this->freetimeSlot[$date][$hour][$minute])) {
            return $this->freetimeSlot[$date][$hour][$minute];
        }

        return null;
    }

    public function getCustomTime($date, $hour, $minute)
    {
        if (!empty($this->customTimeSlot[$date][$hour][$minute])) {
            return $this->customTimeSlot[$date][$hour][$minute];
        }

        return null;
    }

    public function getPlustime($serviceId){
        if(empty($this->plustime[$serviceId])){
            $employeeService = EmployeeService::where('employee_id', $this->id)
                    ->where('service_id', $serviceId)->first();
            $this->plustime[$serviceId] = (!empty($employeeService)) ? $employeeService->plustime : 0;
        }
        return $this->plustime[$serviceId];
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function getIsActiveAttribute()
    {
        return (bool) $this->attributes['is_active'];
    }

    /**
     * Return absolute URL of the avatar
     *
     * @return string
     */
    public function getAvatarUrl()
    {
        if (empty($this->attributes['avatar'])) {
            return asset('assets/img/mm.png');
        }

        return asset(Config::get('varaa.upload_folder').'/avatars/'.$this->attributes['avatar']);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function defaultTimes()
    {
         return $this->hasMany('App\Appointment\Models\EmployeeDefaultTime');
    }

    public function services()
    {
        return $this->belongsToMany('App\Appointment\Models\Service', 'as_employee_service');
    }

    public function bookings()
    {
         return $this->hasMany('App\Appointment\Models\Booking');
    }

    public function freetimes()
    {
         return $this->hasMany('App\Appointment\Models\EmployeeFreetime');
    }

    public function employeeCustomTimes()
    {
         return $this->hasMany('App\Appointment\Models\EmployeeCustomTime');
    }
}
