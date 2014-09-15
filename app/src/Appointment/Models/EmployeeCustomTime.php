<?php namespace App\Appointment\Models;
class EmployeeCustomTime extends \App\Core\Models\Base
{
    protected $table = 'as_employee_custom_time';

    public $fillable = ['date'];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }

    public function freetimes()
    {
         return $this->hasMany('App\Appointment\Models\CustomTime');
    }
}
