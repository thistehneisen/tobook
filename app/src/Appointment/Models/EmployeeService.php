<?php namespace App\Appointment\Models;

class EmployeeService extends \App\Core\Models\Base
{
    public $timestamps = false;
    protected $table = 'as_employee_service';

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
