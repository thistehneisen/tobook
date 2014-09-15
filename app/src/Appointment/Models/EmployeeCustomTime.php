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
