<?php namespace App\Appointment\Controllers;

use View, Input, Confide, Util, Config, Event, Session;
use Carbon\Carbon;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;

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

        // show employee view by default if business has only 1 employee
        if (count($employees) === 1) {
            return $this->employee($employees[0]->id, $date);
        }

        $date = (empty($date)) ? Carbon::today() : $date;

        if (!$date instanceof Carbon) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $date);
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }
        $cutId = Session::get('cutId', 0);

        $workingTimes = $this->getDefaultWorkingTimes($date);

        //TODO settings for day off such as Sunday
        return View::make('modules.as.index.index', [
                'employeeId'   => null, //because use the same view with employee
                'employees'    => $employees,
                'workingTimes' => $workingTimes,
                'date'         => $date,
                'cutId'        => $cutId,
            ]);
    }

    public function employee($id = null, $date = null)
    {
        $employees = Employee::ofCurrentUser()->get();
        $employee  = Employee::ofCurrentUser()->find($id);
        $date = (empty($date)) ? Carbon::today() : $date;
        if (!$date instanceof Carbon) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $date, Config::get('app.timezone'));
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }
        $workingTimes = $this->getDefaultWorkingTimes($date, null, $employee);

        $cloneDate = with(clone $date);
        foreach (range(1, 7) as $day) {
           $weekDaysFromDate[$cloneDate->format('l')] = $cloneDate->toDateString();
           $cloneDate->addDay();
        }

        $cutId = Session::get('cutId', 0);

        return View::make('modules.as.index.employee', [
                'employeeId'       => $id,
                'selectedEmployee' => $employee,
                'employees'        => $employees,
                'workingTimes'     => $workingTimes,
                'weekDaysFromDate' => $weekDaysFromDate,
                'date'             => $date,
                'cutId'            => $cutId
            ]);
    }
}
