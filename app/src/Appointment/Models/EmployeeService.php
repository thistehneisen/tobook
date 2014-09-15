<?php namespace App\Appointment\Models;

class EmployeeService extends \Eloquent
{
    protected $table = 'as_employee_service';

    public $timestamps = false;

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }

    public function service()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }
}
