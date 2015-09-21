<?php namespace App\Appointment\Controllers;

use Input, View, Request;
use Carbon\Carbon;
use App\Appointment\Models\Service;
use App\Appointment\Models\Employee;
use App\Appointment\Reports\Statistics;
use App\Appointment\Reports\MonthlyCalendar;
use App\Appointment\Reports\MonthlyStatistics;

class Reports extends AsBase
{
    protected $viewPath = 'modules.as.reports';

    public function __construct()
    {
        parent::__construct();

        $employees = Employee::ofCurrentUser()->get();
        View::share('employees', $employees);
    }

    /**
     * Show statistics information
     *
     * @return View
     */
    public function statistics()
    {
        $today = Carbon::now();
        $start = Input::has('start')
            ? carbon_date(Input::get('start'))
            : $today->copy()->subMonth();
        $end = Input::has('end')
            ? carbon_date(Input::get('end'))
            : $today;
        $selectedService = (int) Input::get('service', 0);

        $serviceOptions = [0 => trans('common.options_all')] + Service::ofCurrentUser()->lists('name', 'id');

        return $this->render('statistics', [
            'serviceOptions'    => $serviceOptions,
            'selectedService'   => $selectedService,
            'start'             => str_date($start),
            'end'               => str_date($end),
            'report'            => new Statistics($selectedService, $start, $end)
        ]);
    }

    /**
     * Show monthly report
     *
     * @return View
     */
    public function monthlyReport()
    {
        return $this->render('monthly_report', [
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
        $date = Input::has('date')
            ? new Carbon(Input::get('date'))
            : Carbon::now();
        $employeeId = Input::get('employee');

        $calendar = $this->generateCalendar($date);
        $report = new MonthlyCalendar($date, $employeeId);

        $next = with(clone $date)->addMonth();
        $prev = with(clone $date)->subMonth();

        // Merge report to calendar
        $i = array_search(1, $calendar);
        $data = $report->get();
        foreach ($data as $day => $item) {
            $item['day'] = $day;
            $calendar[$i++] = $item;
        }

        return $this->render('stat.calendar', [
            'date'     => $date,
            'next'     => $next,
            'prev'     => $prev,
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

        $report = new MonthlyStatistics($date, $employeeId);
        $currentMonth = $report->get();
        $report = new MonthlyStatistics($prev, $employeeId);
        $lastMonth = $report->get();

        foreach (['revenue', 'bookings','occupation_percent'] as $field) {
            $currentMonth['gap'][$field] = $currentMonth[$field] - $lastMonth[$field];
        }

        return $this->render('stat.monthly', [
            'next'    => $next,
            'prev'    => $prev,
            'monthly' => [$lastMonth, $currentMonth],
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
