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

    public static function getOverlappedFreetimes($employeeId, $date, Carbon $startTime, Carbon $endTime, $id = null)
    {
        $freetimes = null;

        if ($date instanceof Carbon) {
            $date = $date->toDateString();
        }

        $query = self::where('date', $date)
            ->where('employee_id', $employeeId)
            ->whereNull('deleted_at');

        $query = self::applyDuplicateFilter($query, $startTime, $endTime);

        if (!empty($id)) {
            $query->where('id','!=', $id);
        }

        $freetimes = $query->get();

        return $freetimes;
    }

     /**
     * Return a query with conditions for checking duplicate booking
     * @return Illuminate\Database\Query\Builder
     */
    public static function applyDuplicateFilter($query, $startTime, $endTime)
    {
        return $query->where(function ($query) use ($startTime, $endTime) {
            return $query->where(function ($query) use ($startTime, $endTime) {
                return $query->where('as_employee_freetime.start_at', '>=', $startTime->toTimeString())
                     ->where('as_employee_freetime.start_at', '<', $endTime->toTimeString());
            })->orWhere(function ($query) use ($endTime, $startTime) {
                 return $query->where('as_employee_freetime.end_at', '>', $startTime->toTimeString())
                      ->where('as_employee_freetime.end_at', '<=', $endTime->toTimeString());
            })->orWhere(function ($query) use ($startTime) {
                 return $query->where('as_employee_freetime.start_at', '<', $startTime->toTimeString())
                      ->where('as_employee_freetime.end_at', '>', $startTime->toTimeString());
            })->orWhere(function ($query) use ($startTime, $endTime) {
                 return $query->where('as_employee_freetime.start_at', '=', $startTime->toTimeString())
                      ->where('as_employee_freetime.end_at', '=', $endTime->toTimeString());
            });
        });
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

    public function getTypeAttribute()
    {
        return (int) $this->attributes['type'];
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
