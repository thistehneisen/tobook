<?php namespace App\Appointment\Models;
use Config;
use Carbon\Carbon;

class Employee extends \App\Core\Models\Base
{
    protected $table = 'as_employees';

    public $fillable = ['name', 'email', 'phone', 'avatar', 'description', 'is_subscribed_email', 'is_subscribed_sms', 'is_active'];

    protected $rulesets = [
        'saving' => [
            'name'  => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]
    ];

    private $bookedSlot = [];
    private $bookingList = [];

    public function getDefaultTimes()
    {
        $defaultTimes = $this->defaultTimes;
        if($defaultTimes->isEmpty()){
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

    public function getDefaulTimesByDay($day){
        $defaultTimes = $this->getDefaultTimes();
        return $defaultTimes->filter(function($item) use ($day) {
            return $item->type === $day;
        });
    }

    public function getTodayDefaultStartAt(){
         //TODO change thu to today weekday
        $todayStartAt = $this->getDefaulTimesByDay('thu')->first()->start_at;
        return $todayStartAt;
    }

    public function getTodayDefaultEndAt(){
        //TODO change thu to today weekday
        $todayEndAt = $this->getDefaulTimesByDay('thu')->first()->end_at;
        return $todayEndAt;
    }

    //TODO change to another method to compare time
    public function getSlotClass($date, $hour, $minute)
    {
        $selectedDate = Carbon::createFromFormat('Y-m-d', $date);
        $class = 'inactive';
        //working time
        $rowTime = Carbon::createFromTime($hour, $minute, 0, Config::get('app.timezone'));
        list($startHour, $startMinute) = explode(':', $this->getTodayDefaultStartAt());
        $startAt =  Carbon::createFromTime($startHour, $startMinute, 0, Config::get('app.timezone'));
        list($endHour, $endMinute) = explode(':', $this->getTodayDefaultEndAt());
        $endAt = Carbon::createFromTime($endHour, $endMinute, 0, Config::get('app.timezone'));

        if($rowTime >= $startAt && $rowTime <= $endAt){
            $class = 'active';
        } else {
            $class = 'unactive';
        }

        // get booking only certain date
        if(empty($this->bookingList[$selectedDate->toDateString()])){
            $this->bookingList = $this->bookings()->where('date', $selectedDate->toDateString())->get();
        }
        foreach ($this->bookingList as $booking) {
            $bookingDate =  Carbon::createFromFormat('Y-m-d', $booking->date);
            if($bookingDate == $selectedDate){
                list($startHour, $startMinute, $startSecond) = explode(':', $booking->start_at);
                $bookingStartAt =  Carbon::createFromTime($startHour, $startMinute, 0, Config::get('app.timezone'));
                $bookingEndAt   =  with(clone $bookingStartAt)->addMinutes($booking->total);
                if($rowTime >= $bookingStartAt && $rowTime <= $bookingEndAt){
                    $class = 'booked';
                    if($rowTime == $bookingStartAt){
                        $class .= ' slot-booked-head';
                    } else {
                        $class .= ' slot-booked-body';
                    }
                    $this->bookedSlot[$selectedDate->toDateString()][(int)$startHour][(int)$startMinute] = $booking;
                }
            }
        }

        return $class;
    }

    public function getBooked($date, $hour, $minute)
    {
        if(!empty($this->bookedSlot[$date][$hour][$minute])){
            return $this->bookedSlot[$date][$hour][$minute];
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

    public function services(){
        return $this->belongsToMany('App\Appointment\Models\Service', 'as_employee_service');
    }

    public function bookings()
    {
         return $this->hasMany('App\Appointment\Models\Booking');
    }
}
