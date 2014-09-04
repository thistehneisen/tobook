<?php namespace App\Appointment\Models;
use Carbon\Carbon;
class EmployeeFreetime extends \App\Core\Models\Base
{
    protected $table = 'as_employee_freetime';

    public $fillable = ['date', 'start_at', 'end_at', 'description'];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user(){
        return $this->belongsTo('App\Core\Models\User');
    }

    public function employee(){
        return $this->belongsTo('App\Appointment\Models\Employee');
    }

}
