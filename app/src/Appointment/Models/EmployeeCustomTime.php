<?php namespace App\Appointment\Models;
class EmployeeCustomTime extends \App\Core\Models\Base
{
    protected $table = 'as_employee_custom_time';

    public $fillable = ['date', 'start_at', 'end_at', 'is_day_off'];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }
}
