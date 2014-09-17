<?php namespace App\Appointment\Controllers;

use View, Input, Confide, Util, Config, Event;
use App\Core\Controllers\Base;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;
use Carbon\Carbon;
class Index extends AsBase
{
    /**
     * Show booking calendar
     *
     * @return View
     */
    public function index($date = null)
    {
        $employees = Employee::ofCurrentUser()->orderBy('order')->get();
        $date = (empty($date)) ? Carbon::today() : $date;

        if (!$date instanceof Carbon) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $date);
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }

        $workingTimes = $this->getDefaultWorkingTimes($date);
        //TODO settings for day off such as Sunday

        return View::make('modules.as.index.index', [
                'employeeId'   => null,//because use the same view with employee
                'employees'    => $employees,
                'workingTimes' => $workingTimes,
                'date'         => $date
            ]);
    }

    public function employee($id = null, $date = null)
    {
        $employees = Employee::ofCurrentUser()->get();
        $employee  = Employee::ofCurrentUser()->find($id);
        $workingTimes = $employee->getDefaultWorkingTimes();
        $date = (empty($date)) ? Carbon::today() : $date;
        if (!$date instanceof Carbon) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $date, Config::get('app.timezone'));
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }

        $cloneDate = with(clone $date);
        foreach (range(1, 7) as $day) {
           $weekDaysFromDate[$cloneDate->format('l')] = $cloneDate->toDateString();
           $cloneDate->addDay();
        }

        return View::make('modules.as.index.employee', [
                'employeeId'       => $id,
                'selectedEmployee' => $employee,
                'employees'        => $employees,
                'workingTimes'     => $workingTimes,
                'weekDaysFromDate' => $weekDaysFromDate,
                'date'             => $date
            ]);
    }
}
