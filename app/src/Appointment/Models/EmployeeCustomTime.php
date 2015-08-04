<?php namespace App\Appointment\Models;

use Config, Carbon;
use App\Appointment\Models\EmployeeFreetime;
use App\Appointment\Models\Booking;

class EmployeeCustomTime extends \App\Appointment\Models\Base
{
    protected $table = 'as_employee_custom_time';

    public $fillable = ['date'];

    private $workingHours = -1;


    //Currently don't use attribute because can break other code
    public function getStartAt()
    {
        if(empty($this->customTime)) {
            return Carbon\Carbon::now();
        }
        $startAt =  \Carbon\Carbon::createFromFormat('H:i:s', $this->customTime->start_at, Config::get('app.timezone'));
        return $startAt;
    }

    //Currently  don't use attribute because can break other code
    public function getEndAt()
    {
        if(empty($this->customTime)) {
            return Carbon\Carbon::now();
        }
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
        if(!empty($this->customTime)) {
            if ($this->customTime->is_day_off) {
               $this->workingHours = 0.;
            }
        }

        if($this->workingHours === -1) {
            $this->workingHours = $this->getEndAt()->diffInMinutes($this->getStartAt());
        }

        //Count all personal freetime
        $personalFreetimes = $this->employee->freetimes()
                ->where('date', '=', $this->date)
                ->where('type', '=', EmployeeFreetime::PERSONAL_FREETIME)->get();

        foreach ($personalFreetimes as $freetime) {
            $this->workingHours -= $freetime->getMinutes();
        }

        //Count all working freetime outside working hours
         $workingFreetimes = $this->employee->freetimes()
            ->where('date', '=', $this->date)
            ->where('type', '=', EmployeeFreetime::WOKRING_FREETIME)
            ->where(function($query){
                $query->where('start_at', '<=', $this->getStartAt()->toTimeString())
                ->orWhere('start_at', '>=', $this->getEndAt()->toTimeString());
            })->get();

        foreach ($workingFreetimes as $freetime) {
            $this->workingHours += $freetime->getMinutes();
        }

        //Count all working hours in white slots
        $overTimeBookings = $this->employee->bookings()->where('date', '=', $this->date)
            ->where(function($query){
                $query->where('start_at', '<=', $this->getStartAt()->toTimeString())
                ->orWhere('start_at', '>=', $this->getEndAt()->toTimeString());
            })->get();

        foreach ($overTimeBookings as $booking) {
            $this->workingHours += $booking->getMinutes();
        }

        return (double) $this->workingHours / 60;
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
