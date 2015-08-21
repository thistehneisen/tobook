<?php namespace App\Appointment\Models;

class EmployeeDefaultTime extends \App\Appointment\Models\Base
{
    protected $table = 'as_employee_default_time';

    public $fillable = ['type', 'start_at', 'end_at', 'is_day_off'];

    protected function getTypes()
    {
        return [ 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
    }

    public function getStartHourIndex()
    {
        list($hours, $minutes, $seconds) = explode(":",  $this->start_at);

        return (int) $hours;
    }

    public function getEndHourIndex()
    {
        list($hours, $minutes, $seconds) = explode(":",  $this->end_at);

        return (int) $hours;
    }

    public function getStartMinuteIndex()
    {
        list($hours, $minutes, $seconds) = explode(":",  $this->start_at);

        return (int) $minutes;
    }

    public function getEndMinuteIndex()
    {
        list($hours, $minutes, $seconds) = explode(":",  $this->end_at);

        return (int) $minutes;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }
}
