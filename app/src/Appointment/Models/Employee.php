<?php namespace App\Appointment\Models;
use Config;

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
