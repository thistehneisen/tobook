<?php namespace App\Appointment\Models;
class EmployeeCustomTime extends \Eloquent
{
    protected $table = 'as_employee_custom_time';

    public $fillable = ['date'];


    //Currently don't use attribute because can break other code
    public function getStartAt()
    {
        if(!property_exists($this->start_at)){
            return;
        }

        $startAt =  Carbon::createFromFormat('H:i:s', $this->start_at, Config::get('app.timezone'));
        return $startAt;
    }

    //Currently  don't use attribute because can break other code
    public function getEndAt()
    {
        if(!property_exists($this->start_at)){
            return;
        }

        $endAt =  Carbon::createFromFormat('H:i:s', $this->end_at, Config::get('app.timezone'));
        return $endAt;
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
