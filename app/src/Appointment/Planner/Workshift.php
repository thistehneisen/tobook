<?php namespace App\Appointment\Planner;

use App, View, Confide, Redirect, Input, Config, Util, Response, Request, Validator, DB, NAT;
use Carbon\Carbon;
use App\Appointment\Models\Employee;
use App\Appointment\Models\CustomTime;

class Workshift
{

    protected $startDate;
    protected $endDate;

    /**
    * Custom times of employees
    */
    protected $employeeTimes = [];

    /**
    * Weekly working hours of each employee
    */
    protected $weekly        = [];

    /**
    * Monthly working hours of each employee
    */
    protected $monthly       = [];

    /**
    * Date range to render the workshift planning table
    */
    protected $dateRange     = [];

    public function __construct($startDate = null, $endDate = null)
    {
        if(!empty($startDate) && !empty($endDate)){
            $this->startDate = $startDate;
            $this->endDate   = $endDate;
            $this->init();
        }
    }

    protected function init()
    {
        $employees = Employee::ofCurrentUser()->get();
        foreach ($employees as $employee) {
            $items = $employee->EmployeeCustomTimes()
                ->with('customTime')
                ->where('date','>=', $this->startDate)
                ->where('date','<=', $this->endDate)
                ->orderBy('date','asc')->get();

            foreach ($items as $item) {
                $this->employeeTimes[$item->date][$employee->id] = $item;
                $date = new Carbon($item->date);

                //Collect weekly summary data for each employee
                if (empty($this->weekly[$date->weekOfYear][$employee->id])) {
                    $this->weekly[$date->weekOfYear][$employee->id] = $item->getWorkingHours();
                } else {
                    $this->weekly[$date->weekOfYear][$employee->id] += $item->getWorkingHours();
                }

                //Collect monthly summary data for each employee
                if (empty($this->montly[$date->month][$employee->id])) {
                    $this->montly[$date->month][$employee->id] = $item->getWorkingHours();
                } else {
                    $this->montly[$date->month][$employee->id] += $item->getWorkingHours();
                }
            }
        }
    }

    /**
    * Calculate and return date range to render the workshift planning table
    *
    * @return array
    */
    public function getDateRange()
    {
        $start = $this->startDate->copy();
        $days  = $start->copy()->diffInDays($this->endDate);
        foreach (range(1, $days+1) as $day) {
            if (!empty($this->employeeTimes[$start->toDateString()])) {
                $this->dateRange[$start->toDateString()]['date']      = $start->copy();
                $this->dateRange[$start->toDateString()]['employees'] = $this->employeeTimes[$start->toDateString()];
            } else {
                $this->dateRange[$start->toDateString()]['date']      = $start->copy();
                $this->dateRange[$start->toDateString()]['employees'] = [];
            }
            $start->addDay();
        }

        return $this->dateRange;
    }

    /**
    * Return weekly working hours of each employee
    *
    * @return array
    */
    public function getWeekSummary()
    {
        return $this->weekly;
    }

    /**
    * Return monthly working hours of each employee
    *
    * @return array
    */
    public function getMonthSummary()
    {
        return $this->monthly;
    }

    /**
     * Get formated custom time to display on dropbox
     * [
     *      '1' => 'name (8:00 - 17:00)'
     *      '2' => 'name (10:00 - 16:00)'
     *      '3' => 'name (14:45 - 18:00)'
     *       ...
     * ]
     * @return array
     */
    public function getDisplayCustomTimes()
    {
        $format = 'CONCAT(name, " (", TIME_FORMAT(start_at, "%H:%i"),
                               " - ", TIME_FORMAT(end_at, "%H:%i"),")") AS name';

        //JSON will sort the id by ascending order automatically
        //Append @ in order to avoid auto sorting
        $customTimes = CustomTime::ofCurrentUser()
            ->orderBy('start_at')
            ->orderBy('end_at')
            ->select(DB::raw($format), DB::raw('CONCAT("@",id) as id'))
            ->lists('name', 'id');

        $customTimes = [0 => trans('common.options_select')] + $customTimes;

        return $customTimes;
    }
}
