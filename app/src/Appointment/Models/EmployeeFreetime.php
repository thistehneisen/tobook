<?php namespace App\Appointment\Models;
use Carbon\Carbon;
use Config;
class EmployeeFreetime extends \App\Appointment\Models\Base
{
    protected $table = 'as_employee_freetime';

    public $fillable = ['date', 'start_at', 'end_at', 'description', 'type'];

    const PERSONAL_FREETIME = 1;
    const WOKRING_FREETIME  = 2;

    public function getLength()
    {
       return (int) $this->getStartAt()->diffInMinutes($this->getEndAt());
    }
    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getStartTimeAttribute()
    {
        return new Carbon($this->attributes['date'].' '.$this->attributes['start_at']);
    }

    public function getEndTimeAttribute()
    {
        return new Carbon($this->attributes['date'].' '.$this->attributes['end_at']);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }

}
