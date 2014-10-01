<?php namespace App\Appointment\Controllers\Embed;

use Input, Response, Carbon\Carbon, Session, Redirect;
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
        $employees = $service->employees()->where('is_active', true)->get();

        return $this->render('employees', [
            'employees' => $employees
        ]);
    }

    /**
     * Get table table of an employee
     *
     * @return View
     */
    public function getTimetable()
    {
        $today    = Carbon::today();
        $date     = Input::has('date') ? new Carbon(Input::get('date')) : $today;
        $hash     = Input::get('hash');
        $employee = Employee::findOrFail(Input::get('employeeId'));
        $service  = Service::findOrFail(Input::get('serviceId'));

        if ($date->lt($today)) {
            $date = $today->copy();
        }
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

                $time = $date->copy()->hour($hour)->minute($shift);
                $isActive = $slotClass === 'active'
                    || $slotClass === 'custom active';
                if ($time->gt(Carbon::now()) && $isActive) {
                    $timetable[] = sprintf('%02d:%02d', $hour, $shift);
                }
            }
        }

        $i = 1;
        $startDate = $date->copy();
        while ($i <= 2) {
            $startDate = $startDate->subDay();

            if ($startDate->lt($today)) {
                $startDate = $today->copy();
                break;
            }
            $i++;
        }

        return $this->render('timetable', [
            'date'      => $date,
            'startDate' => $startDate,
            'prev'      => $date->copy()->subDay(),
            'next'      => $date->copy()->addDay(),
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
        // Empty the cart first
        Session::forget('carts');

        return $this->render('checkout', [
            'user'         => $this->getUser(),
            'booking_info' => $this->getBookingInfo(),
        ]);
    }

    protected function getBookingInfo()
    {
        if (!empty(Session::get('booking_info'))) {
            return Session::get('booking_info');
        }
    }

    /**
     * Validate submitted data
     *
     * @return View
     */
    public function confirm()
    {
        $v = $this->getConfirmationValidator();
        if ($v->fails()) {
            // Flash old input
            Input::flash();

            return $this->render('checkout', [
                'user'         => $this->getUser(),
                'booking_info' => $this->getBookingInfo(),
            ])->with('errors', $v->errors());
        }

        // We will show all information and ask for confirmation
        $data             = Input::all();
        $data['date']     = new Carbon($data['date']);
        $data['service']  = Service::find(Input::get('serviceId'));
        $data['employee'] = Employee::findOrFail(Input::get('employeeId'));

        return $this->render('confirm', $data);
    }
}