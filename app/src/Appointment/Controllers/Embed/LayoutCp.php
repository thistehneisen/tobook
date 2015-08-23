<?php namespace App\Appointment\Controllers\Embed;

use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use Carbon\Carbon;
use Input;
use Response;

class LayoutCp extends Base
{
    use Layout;

    public function getServices($hash)
    {
        $data = $this->handleIndex($hash);
        return Response::json([
            'categories' => $data['categories'],
            'business' => $data['user']->business,
        ]);
    }

    public function getEmployees()
    {
        $hash = Input::get('hash');
        $user = $this->getUser($hash);
        $serviceId = Input::get('serviceId');
        $service = Service::ofUser($user)->findOrFail($serviceId);

        return Response::json($service->employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->name,
            ];
        }));
    }

    public function getTimetable()
    {
        $today      = new Carbon('2015-08-24');
        $date       = Input::has('date') ? new Carbon(Input::get('date')) : $today;
        $dateStr    = $date->toDateString();
        $hash       = Input::get('hash');
        $service    = Service::findOrFail(Input::get('serviceId'));
        $employeeId = (int) Input::get('employeeId');
        $serviceTime = null;

        if (Input::has('serviceTimeId')) {
            if (Input::get('serviceTimeId') !== 'default') {
                $serviceTime = $service->serviceTimes()
                    ->findOrFail(Input::get('serviceTimeId'));
            }
        }

        //Withdrawal time feature
        list($start, $final, $maxWeeks) = $this->getMinMaxDistanceDay($hash);

        if ($date->lt($start)) {
            $date = $start->copy();
        }

        $startDate = $date->copy()->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();
        $nextWeek = $endDate->copy()->addDay();
        $prevWeek = $startDate->copy()->subDay();

        $dates = [];
        $i = $startDate->copy();
        while ($i->lte($endDate)) {
            $dates[] = [
                'dayOfWeek' => $i->format('D'),
                'date' => $i->toDateString(),
                'niceDate' => $i->format('d.m'),
            ];
            $i->addDay();
        }

        // Get timetable data
        $timetable = [];
        if ($employeeId > 0) {
            $employee = Employee::findOrFail($employeeId);
            $timetable = $this->getTimetableOfSingle($employee, $service, $date, $serviceTime);
        } else {
            $timetable = $this->getTimetableOfAnyone($service, $date, $serviceTime);
        }

        $calendar = [];
        foreach ($timetable as $time => $employee) {
            $calendar[] = [
                'time' => $time,
                'date' => $dateStr,
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                ]
            ];
        }

        return Response::json([
            'dates'        => $dates,
            'selectedDate' => $dateStr,
            'prevWeek'     => $prevWeek->toDateString(),
            'nextWeek'     => $nextWeek->toDateString(),
            'calendar'     => $calendar,
        ]);
    }
}
