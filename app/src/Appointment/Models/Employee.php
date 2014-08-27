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
    public function getSlotClass($hour, $minute){
       $class = 'inactive';
       $rowTime = Carbon::createFromTime($hour, $minute, 0, 'Europe/Helsinki');
       list($start_hour, $start_minute) = explode(':', $this->getTodayDefaultStartAt());
       $startAt =  Carbon::createFromTime($start_hour, $start_minute, 0, 'Europe/Helsinki');
       list($end_hour, $end_minute) = explode(':', $this->getTodayDefaultEndAt());
       $endAt = Carbon::createFromTime($end_hour, $end_minute, 0, 'Europe/Helsinki');
       if($rowTime >= $startAt && $rowTime <= $endAt){
            $class = 'active';
       }
       return $class;
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
}
