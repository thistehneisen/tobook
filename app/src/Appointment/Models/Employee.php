<?php namespace App\Appointment\Models;

use Config, Util;
use Carbon\Carbon;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Slot\Strategy;
use App\Appointment\Models\Slot\Context;
use App\Appointment\Models\Slot\Backend;
use App\Appointment\Models\Slot\Frontend;
use App\Appointment\Models\Slot\Next;

class Employee extends \App\Appointment\Models\Base
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

    /**
     * Genereate working times table this employee for using in Layout 2 and 3
     * If default working times of an employee is empty, try to use custom time of the date
     *
     * @param Carbon $date
     * @return array
     */
    public function getWorkingTimesByDate($date, &$end)
    {
        $time         = $this->getDefaulTimesByDayOfWeek($date->dayOfWeek);
        $startTime    = null;
        $endTime      = null;
        $workingTimes = [];

        list($startHour, $startMinute, $endHour, $endMinute) = $this->getStartTimeEndTime($time);

        /* if default time is 0:00 to 0:00 try to get custom time */
        if (($startHour + $endHour + $startMinute + $endMinute) === 0) {
            $empCustomTime = $this->employeeCustomTimes()
                    ->with('customTime')
                    ->where('date', $date->toDateString())
                    ->first();

            if(!empty($empCustomTime)) {
                list($startHour, $startMinute, $endHour, $endMinute) = $this->getStartTimeEndTime($empCustomTime->customTime);
            }
        }
        //for use in Layout::getTimetableOfSingle
        $end =  Carbon::createFromFormat('H:i', sprintf('%02d:%02d', $endHour, $endMinute));

        if (($startHour + $endHour + $startMinute + $endMinute) === 0) {
            return $workingTimes;
        }

        for ($i = $startHour; $i<= $endHour; $i++) {
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

    public function getStartTimeEndTime($time)
    {
        if(empty($time)) {
            return [0, 0, 0, 0];
        }

        if (intval($time->is_day_off) === 0) {
            $startTime = Carbon::createFromFormat('H:i:s', $time->start_at);
            $endTime =  Carbon::createFromFormat('H:i:s', $time->end_at);
        }

        $startHour   = (int)!empty($startTime) ? $startTime->hour   : 0;
        $startMinute = (int)!empty($startTime) ? $startTime->minute : 0;
        $endHour     = (int)!empty($endTime)   ? $endTime->hour     : 0;
        $endMinute   = (int)!empty($endTime)   ? $endTime->minute   : 0;

        return [$startHour, $startMinute, $endHour, $endMinute];
    }

    /**
     * Return default working time of an employee
     * @param Carbon $date
     * @return array
     */
    public function getDefaultWorkingTimes($date = null)
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

        if(!empty($date)){
            $startDate      = $date;
            $endDate        = with(clone $date)->addDays(6);
            $lastestBooking = Booking::getLastestBookingEndTimeInRange($startDate->toDateString(), $endDate->toDateString());
            if(!empty($lastestBooking)){
                $lastestEndTime = $lastestBooking->getEndAt();
                if(($lastestEndTime->hour   >= $endHour)
                && ($lastestEndTime->minute >  $endMinute))
                {
                    $endHour   = $lastestEndTime->hour;
                    $endMinute = ($lastestEndTime->minute % 15)
                        ? $lastestEndTime->minute + (15 - ($lastestEndTime->minute % 15))
                        : $lastestEndTime->minute;
                }
            }
        }
        $endHour   = ($endMinute == 0) ? $endHour - 1 : $endHour;
        $endMinute = ($endMinute == 0 || $endMinute == 60) ? 45 : $endMinute;
        for ($i = $startHour; $i<= $endHour; $i++) {
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

    /**
     * Return the effective end time of a date
     * Consider default working time and work-shift planning time
     *
     * @param \Carbon\Carbon $date
     * @return \Carbon\Carbon
     */
    public function getEffectiveEndAtByDate($date)
    {
        $defaultEndAt = $this->getTodayDefaultEndAt($date->weekday);

        $empCustomTime = $this->employeeCustomTimes()
                    ->with('customTime')
                    ->where('date', $date->toDateString())
                    ->first();

        $endAt = (!empty($empCustomTime))
            ? $empCustomTime->customTime->getEndAt()
            : $defaultEndAt;

        return $endAt;
    }

    /**
     * Check if the booking start time and end time overlap with
     * current employee freetime
     *
     * @param string $date
     * @param Carbon $startTime
     * @param Carbon $endTime
     *
     * @return boolean
     */
    public function isOverllapedWithFreetime($date, $startTime, $endTime)
    {
        $freetime = $this->freetimes()
            ->where('date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                return $query->where(function ($query) use ($startTime, $endTime) {
                    return $query->where('start_at', '>=', $startTime->toTimeString())
                         ->where('start_at', '<', $endTime->toTimeString());
                })->orWhere(function ($query) use ($endTime, $startTime) {
                     return $query->where('end_at', '>', $startTime->toTimeString())
                          ->where('end_at', '<=', $endTime->toTimeString());
                })->orWhere(function ($query) use ($startTime) {
                     return $query->where('start_at', '<', $startTime->toTimeString())
                          ->where('end_at', '>', $startTime->toTimeString());
                })->orWhere(function ($query) use ($startTime, $endTime) {
                     return $query->where('start_at', '=', $startTime->toTimeString())
                          ->where('end_at', '=', $endTime->toTimeString());
                });
            })->first();

        return (!empty($freetime)) ? true : false;
    }

    public function getTodayDefaultStartAt($weekday = null)
    {
        if ($weekday === null) {
            $weekday = Carbon::now()->dayOfWeek;
        }
        $dayOfWeek = Util::getDayOfWeekText($weekday);
        $todayStartAt = $this->getDefaulTimesByDay($dayOfWeek)->first()->start_at;
        return (new Carbon($todayStartAt));
    }

    public function getTodayDefaultEndAt($weekday = null)
    {
        if ($weekday === null) {
            $weekday = Carbon::now()->dayOfWeek;
        }
        $dayOfWeek = Util::getDayOfWeekText($weekday);
        $todayEndAt = $this->getDefaulTimesByDay($dayOfWeek)->first()->end_at;
        return (new Carbon($todayEndAt));
    }

    //TODO change to another method to compare time
    public function getSlotClass($date, $hour, $minute, $context = 'backend', $service = null)
    {
        //Cache by date for employee view
        if(empty($this->strategy[$date])){
            $class = "App\\Appointment\\Models\\Slot\\" .ucfirst($context);
            if (class_exists($class)) {
                $this->strategy[$date] = new $class();
            } else {
                $this->strategy[$date] = new Backend();
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

    public function getPlustime($serviceId)
    {
        if (empty($this->plustime[$serviceId])) {
            $employeeService = EmployeeService::where('employee_id', $this->id)
                    ->where('service_id', $serviceId)->first();
            $this->plustime[$serviceId] = (!empty($employeeService)) ? $employeeService->plustime : 0;
        }
        return $this->plustime[$serviceId];
    }

    /**
     * @return array
     */
    public function getWorkshiftPlan($date)
    {

        list($current, $startOfMonth, $endOfMonth) = $this->getWorkshiftDate($date);

        $items = $this->employeeCustomTimes()
            ->with('customTime')
            ->where('date','>=', $startOfMonth)
            ->where('date','<=', $endOfMonth)
            ->orderBy('date','asc')->get();

        $customTimesList = [];

        foreach ($items as $item) {
           $customTimesList[$item->date] = $item;
        }

        $currentMonths = [];
        $startDay      = with(clone $current->startOfMonth());
        foreach (range(1, $current->daysInMonth) as $day) {
            if (!empty($customTimesList[$startDay->toDateString()])) {
                $currentMonths[$startDay->toDateString()] = $customTimesList[$startDay->toDateString()];
            } else {
                $currentMonths[$startDay->toDateString()] = with(clone $startDay);
            }
            $startDay->addDay();
        }
        return [$customTimesList, $currentMonths];
    }

    /**
     * Create Carbon date object for a given yyyy-mm string
     *
     * @return array(Carbon\Carbon, string, string)
     */
    public function getWorkshiftDate($date)
    {
        $current      = Carbon::now();

        if (!empty($date)) {
            try {
                $current = Carbon::createFromFormat('Y-m-d', $date . '-01');
            } catch(\Exception $ex) {
                $current = Carbon::now();
            }
        }
        $startOfMonth = $current->startOfMonth()->toDateString();
        $endOfMonth   = $current->endOfMonth()->toDateString();
        return [$current, $startOfMonth, $endOfMonth];
    }

    /**
     * Get random active service which is belong to a show-front category
     * @return App\Appointment\Models\Service
     */
    public function getRandomActiveService()
    {
        return Service::where('as_services.user_id', $this->user->id)
            ->join('as_service_categories','as_service_categories.id', '=', 'as_services.category_id')
            ->join('as_employee_service', 'as_employee_service.service_id', '=', 'as_services.id')
            ->join('as_employees', 'as_employees.id', '=', 'as_employee_service.employee_id')
            ->where('as_employees.is_active', true)
            ->where('as_service_categories.is_show_front', true)
            ->select('as_services.*')
            ->orderBy(\DB::raw('RAND()'))
            ->first();
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
        return (isset($this->attributes['is_active'])) ? (bool) $this->attributes['is_active'] : true;
    }


    private function getAvatarPath()
    {
        if (file_exists(Config::get('varaa.upload_folder').'/avatars/'.$this->attributes['avatar'])) {
            return Config::get('varaa.upload_folder').'/avatars/'.$this->attributes['avatar'];
        } else {
            return Config::get('varaa.upload_folder').'/images/'.$this->attributes['avatar'];
        }
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

        return Util::thumbnail($this->getAvatarPath(), 200, 200);
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
