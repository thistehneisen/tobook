<?php namespace App\Appointment\Controllers;

use View, Input, Confide, Util, Config, Event, Session;
use Carbon\Carbon;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;
use App\Appointment\Planner\Workshift;

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
                $date = Carbon::createFromFormat(str_date_format(), $date);
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }
        $cutId = Session::get('cutId', 0);

        $workingTimes = $this->getDefaultWorkingTimes($date);

        $planner      = new Workshift();
        $customTimes  = $planner->getDisplayCustomTimes();

        //TODO settings for day off such as Sunday
        return View::make('modules.as.index.index', [
                'employeeId'   => null, //because use the same view with employee
                'employees'    => $employees,
                'workingTimes' => $workingTimes,
                'date'         => $date,
                'cutId'        => $cutId,
                'user'         => $this->user,
                'customTimes'  => json_encode($customTimes)
            ]);
    }


    /**
     * Display a view for printing backend calendar for all employees
     *
     * @return View
     */
    public function printAll($date = null)
    {   
        /** @var $employees \Illuminate\Support\Collection **/
        $all = Employee::ofCurrentUser()->orderBy('order')->get();
        $employees = [];

        //show employee view by default if business has only 1 employee
        if (count($all) === 1) {
            return $this->printOne($all[0]->id, $date);
        }

        if ($all->count() >= 5) {
            $group = [];
            $count = 0;
            foreach ($all as $employee) {
                $group[] = $employee;
                if (count($group) == 5) {
                    $employees[] = $group;
                    $group = [];
                }
            }
        } else {
            foreach ($all as $employee) {
                $group[] = $employee;
            }
            $employees[] = $group;
            $group = [];
        }


        $date = (empty($date)) ? Carbon::today() : $date;

        if (!$date instanceof Carbon) {
            try {
                $date = Carbon::createFromFormat(str_date_format(), $date);
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }

        $workingTimes = $this->getDefaultWorkingTimes($date);

        $planner      = new Workshift();
        $customTimes  = $planner->getDisplayCustomTimes();

        //TODO settings for day off such as Sunday
        return View::make('modules.as.index.printAll', [
                'employeeId'   => null, //because use the same view with employee
                'groups'       => $employees,
                'workingTimes' => $workingTimes,
                'date'         => $date,
                'cutId'        => 0,
                'user'         => $this->user,
                'customTimes'  => json_encode($customTimes)
            ]);
    }

    /**
     * Display a view for printing backend calendar for one employee
     * 
     * @return View
     */
    public function printOne($id = null, $date = null)
    {
        $employees = Employee::ofCurrentUser()->get();
        $employee  = Employee::ofCurrentUser()->find($id);
        $date = (empty($date)) ? Carbon::today() : $date;
        if (!$date instanceof Carbon) {
            try {
                $date = Carbon::createFromFormat('d.m.Y', $date, Config::get('app.timezone'));
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }
        $workingTimes = $this->getDefaultWorkingTimes($date, null, $employee);

        $start = $date->copy();
        foreach (range(1, 7) as $day) {
           $weekDaysFromDate[$start->format('l')] = str_date($start);
           $start->addDay();
        }

        $cutId = Session::get('cutId', 0);

        $planner      = new Workshift();
        $customTimes  = $planner->getDisplayCustomTimes();

        return View::make('modules.as.index.printOne', [
                'employeeId'       => $id,
                'theEmployee'      => $employee,
                'employees'        => $employees,
                'workingTimes'     => $workingTimes,
                'weekDaysFromDate' => $weekDaysFromDate,
                'date'             => $date,
                'cutId'            => $cutId,
                'user'             => $this->user,
                'customTimes'      => json_encode($customTimes)
            ]);
    }


    /**
     * Handle and reder employee weekly
     * @param integer $id
     * @param string $date
     *
     * @return View
     */
    public function employee($id = null, $date = null)
    {
        $employees = Employee::ofCurrentUser()->get();
        $employee  = Employee::ofCurrentUser()->find($id);
        $date = (empty($date)) ? Carbon::today() : $date;
        if (!$date instanceof Carbon) {
            try {
                $date = Carbon::createFromFormat('d.m.Y', $date, Config::get('app.timezone'));
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }
        $workingTimes = $this->getDefaultWorkingTimes($date, null, $employee);

        $start = $date->copy();
        foreach (range(1, 7) as $day) {
           $weekDaysFromDate[$start->format('l')] = str_date($start);
           $start->addDay();
        }

        $cutId = Session::get('cutId', 0);

        $planner      = new Workshift();
        $customTimes  = $planner->getDisplayCustomTimes();

        return View::make('modules.as.index.employee', [
                'employeeId'       => $id,
                'theEmployee'      => $employee,
                'employees'        => $employees,
                'workingTimes'     => $workingTimes,
                'weekDaysFromDate' => $weekDaysFromDate,
                'date'             => $date,
                'cutId'            => $cutId,
                'user'             => $this->user,
                'customTimes'      => json_encode($customTimes)
            ]);
    }
}
