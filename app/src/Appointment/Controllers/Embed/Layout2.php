<?php namespace App\Appointment\Controllers\Embed;

use Input, Response, Carbon\Carbon, Session, Redirect;
use App\Appointment\Models\Service;
use App\Appointment\Models\Employee;

class Layout2 extends Base
{
    /**
     * @{@inheritdoc}
     */
    public function getTimetable()
    {
        $today    = Carbon::today();
        $date     = Input::has('date') ? new Carbon(Input::get('date')) : $today;
        $hash     = Input::get('hash');
        $service  = Service::findOrFail(Input::get('serviceId'));

        // Calculate date ranges for nav
        $nav = [];
        $i = 1;
        $start = $date->copy();
        while ($i++ <= 5) {
            $end = $start->copy()->addDays(4);
            $nav[] = [
                'start' => $start->copy(),
                'end'   => $end->copy()
            ];

            $start = $end->addDay();
        }

        // Timetable
        $employeeId = (int) Input::get('employeeId');
        $employee = null;
        if ($employeeId > 0) {
            $employee = Employee::findOrFail($employeeId);
        }

        $start = $date->copy();
        $timetable = [];
        $dates = [];
        $i = 1;
        while ($i++ <= 5) {
            if ($employee !== null) {
                $time = $this->getTimetableOfSingle($employee, $service, $start, true);
            } else {
                $time = $this->getTimetableOfAnyone($service, $start, true);
            }

            $dates[]     = $start->copy();
            $timetable[] = $time;

            $start = $start->addDay();
        }

        return $this->render('timetable', [
            'date'      => $date,
            'nav'       => $nav,
            'timetable' => $timetable,
            'dates'     => $dates
        ]);
    }
}
