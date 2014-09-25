<?php namespace App\Appointment\Controllers\Embed;

use Input, Response, Carbon\Carbon, Session;
use App\Appointment\Controllers\Embed;
use App\Appointment\Models\Service;
use App\Appointment\Models\Employee;

class Layout3 extends Embed
{
    /**
     * @{@inheritdoc}
     */
    protected function render($tpl, $data = [])
    {
        // No need to getLayout() everytime
        return parent::render($this->getLayout().'.'.$tpl, $data);
    }

    /**
     * Get all employees available for a service
     *
     * @return View
     */
    public function getEmployees()
    {
        $serviceId = Input::get('serviceId');
        if ($serviceId === null) {
            return Response::json(['message' => 'Missing service ID'], 400);
        }

        $service = Service::with('employees')->findOrFail($serviceId);
        return $this->render('employees', [
            'employees' => $service->employees
        ]);
    }

    /**
     * Get table table of an employee
     *
     * @return View
     */
    public function getTimetable()
    {
        $date     = Input::has('date') ? new Carbon(Input::get('date')) : Carbon::today();
        $hash     = Input::get('hash');
        $employee = Employee::findOrFail(Input::get('employeeId'));
        $service  = Service::findOrFail(Input::get('serviceId'));

        $timetable = [];
        $workingTimes = $this->getDefaultWorkingTimes($date, $hash);
        foreach ($workingTimes as $hour => $minutes) {
            foreach ($minutes as $shift) {
                $slotClass = $employee->getSlotClass(
                    $date->toDateString(),
                    $hour,
                    $shift,
                    'frontend',
                    $service
                );

                if ($slotClass === 'active') {
                    $timetable[] = sprintf('%02d:%02d', $hour, $shift);
                }
            }
        }

        return $this->render('timetable', [
            'date'      => $date,
            'startDate' => with(clone $date)->subDays(2),
            'timetable' => $timetable
        ]);
    }

    /**
     * Show the form for customer to checkout
     *
     * @return View
     */
    public function checkout()
    {
        $booking_info = null;
        if (!empty(Session::get('booking_info'))) {
            $booking_info = Session::get('booking_info');
        }

        return $this->render('checkout', [
            'user'         => $this->getUser(),
            'booking_info' => $booking_info
        ]);
    }
}
