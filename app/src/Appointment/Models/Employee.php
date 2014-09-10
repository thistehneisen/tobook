<?php namespace App\Appointment\Models;
use Config, Util;
use Carbon\Carbon;
use App\Appointment\Models\Slot\Strategy;
use App\Appointment\Models\Slot\Context;
use App\Appointment\Models\Slot\Backend;
use App\Appointment\Models\Slot\Frontend;

class Employee extends \App\Core\Models\Base
{
    protected $table = 'as_employees';

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

    public function setBookedSlot(array $data){
        $this->bookedSlot = $data;
    }

    public function setFreetimeSlot(array $data){
        $this->freetimeSlot = $data;
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

    public function getTodayDefaultStartAt($weekday = null)
    {
        if($weekday === null){
            $weekday = Carbon::now()->dayOfWeek;
        }
        $dayOfWeek = Util::getDayOfWeekText($weekday);
        $todayStartAt = $this->getDefaulTimesByDay($dayOfWeek)->first()->start_at;

        return $todayStartAt;
    }

    public function getTodayDefaultEndAt($weekday = null)
    {
        if($weekday === null){
            $weekday = Carbon::now()->dayOfWeek;
        }
        $dayOfWeek = Util::getDayOfWeekText($weekday);
        $todayEndAt = $this->getDefaulTimesByDay($dayOfWeek)->first()->end_at;

        return $todayEndAt;
    }

    //TODO change to another method to compare time
    public function getSlotClass($date, $hour, $minute, $context = 'backend')
    {
        $strategy = new Backend();
        if($context === 'frontend'){
            $strategy = new FrontEnd();
        }
        $context = new Context($strategy);
        return $context->determineClass($this, $date, $hour, $minute);
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

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function setIsActive($value)
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function getIsActive()
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
}
