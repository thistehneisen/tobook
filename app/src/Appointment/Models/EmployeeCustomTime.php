<?php namespace App\Appointment\Models;
use Config, Carbon;
class EmployeeCustomTime extends \Eloquent
{
    protected $table = 'as_employee_custom_time';

    public $fillable = ['date'];


    //Currently don't use attribute because can break other code
    public function getStartAt()
    {
        $startAt =  \Carbon\Carbon::createFromFormat('H:i:s', $this->customTime->start_at, Config::get('app.timezone'));
        return $startAt;
    }

    //Currently  don't use attribute because can break other code
    public function getEndAt()
    {
        $endAt =  \Carbon\Carbon::createFromFormat('H:i:s', $this->customTime->end_at, Config::get('app.timezone'));
        return $endAt;
    }

    /**
     * Return day of week of this custom time
     * @return int
     */
    public function getDayOfWeek()
    {
        $date = new \Carbon\Carbon($this->date);
        return $date->dayOfWeek;
    }

    public function getWorkingHours()
    {
        if ($this->customTime->is_day_off) {
           return 0;
        }
        return $this->getEndAt()->diffInHours($this->getStartAt());
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }

    public function customTime()
    {
         return $this->belongsTo('App\Appointment\Models\CustomTime');
    }

    public static function getUpsertModel($employeeId, $date)
    {
        $model = self::where('employee_id', $employeeId)
            ->where('date', $date)
            ->first();

       return (!empty($model)) ? $model : (new self());
    }
}
