<?php namespace App\Appointment\Controllers;

use Input, View;
use Carbon\Carbon;
use App\Appointment\Models\Employee;
use App\Appointment\Reports\Statistics;
use App\Appointment\Reports\MonthlyStatistics;

class Stat extends Bookings
{
    public function __construct()
    {
        parent::__construct();

        $employees = Employee::ofCurrentUser()->get();

        View::share('employees', $employees);
    }

    /**
     * Show statistics information of bookings
     *
     * @return View
     */
    public function index()
    {
        return $this->render('statistics', [
            'calendar' => $this->calendar(),
            'monthly'  => $this->monthly()
        ]);
    }

    /**
     * Show stat in the calendar
     *
     * @return View
     */
    public function calendar()
    {
        $now = Carbon::now();

        $calendar = $this->generateCalendar($now);
        $report = new Statistics($now);

        // Merge report to calendar
        $i = array_search(1, $calendar);
        foreach ($report->get() as $day => $data) {
            $data['day'] = $day;
            $calendar[$day - 1 - $i] = $data;
        }

        return $this->render('stat.calendar', [
            'calendar' => $calendar
        ]);
    }

    /**
     * Show stat of monthly basics
     *
     * @return View
     */
    public function monthly()
    {
        $date = Input::has('date')
            ? new Carbon(Input::get('date'))
            : Carbon::now();

        $next = with(clone $date)->addMonth();
        $prev = with(clone $date)->subMonth();

        $employeeId = Input::get('employee');

        $currentMonth = new MonthlyStatistics($date, $employeeId);
        $lastMonth = new MonthlyStatistics($prev, $employeeId);

        return $this->render('stat.monthly', [
            'next'    => $next,
            'prev'    => $prev,
            'monthly' =>  [$lastMonth->get(), $currentMonth->get()],
        ]);
    }

    /**
     * Receive a date and return an array of days in the month with Monday
     * starting first. Maximum 35 elements.
     *
     * @param Carbon $date
     *
     * @return array
     */
    protected function generateCalendar(Carbon $date)
    {
        $calendar = [];

        $start = $date->startOfMonth();
        $daysInMonth = $date->daysInMonth;
        $j = ($start->dayOfWeek === Carbon::SUNDAY)
            ? 6
            : $start->dayOfWeek;

        $i = 0;
        while ($i < $j - 1) {
            $calendar[$i++] = null;
        }

        $i = 1;
        while ($i <= $daysInMonth) {
            $calendar[] = $i++;
        }

        $i = count($calendar);
        while ($i++ < 35) {
            $calendar[] = null;
        }

        return $calendar;
    }
}
