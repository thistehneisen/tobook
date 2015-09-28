<?php namespace App\Appointment\Controllers\Embed;

use Input, Response, Carbon\Carbon, Session, Redirect, Cart;
use App\Appointment\Models\Service;
use App\Appointment\Models\Employee;
use App\Consumers\Models\Consumer;
use Illuminate\Support\ViewErrorBag;

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

        $user = empty($user)
            ? $this->getUser($hash)
            : $user;

        $serviceTime = null;
        if (Input::has('serviceTimeId')) {
            if (Input::get('serviceTimeId') !== 'default') {
                $serviceTime = $service->serviceTimes()
                    ->findOrFail(Input::get('serviceTimeId'));
            }
        }

        // Calculate date ranges for nav
        $nav = [];
        $i = 1;

        // Withdrawal time feature
        list($start, $final, $maxWeeks) = $this->getMinMaxDistanceDay($hash);
        // Always show 7 weeks in this layout
        $maxWeeks = 7;

        // Move to the start of week, so that Monday is always shown
        $date->startOfWeek();
        $start->startOfWeek();

        // if the selected day is not inside the fourth week in the list then use today
        if ($date->copy()->subDays(21) >= $today) {
            $start = $date->copy();
            $start->subDays(21);
        }

        while ($i++ <= $maxWeeks) {
            $j = 0;
            while ($j++ <= 5) {
                $end = $start->copy()->addDays($j);
                // Show all date but don't show any timeslot
                // if ($end >= $final) {
                //     break;
                // }
            }
            $nav[] = (object) [
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
  
        while ($i++ <= 7) {
            if ($employee !== null) {
                $time = $this->getTimetableOfSingle($employee, $service, $start, $serviceTime, true);
            } else {
                $time = $this->getTimetableOfAnyone($service, $start, $serviceTime, true);
            }

            $dates[] = $start->copy();

            $timetable[] = (object) [
                'date' => $start->copy(),
                'time' => ($start->lte($final)) ? $time : [], // don't show timetable > max date
            ];

            $start = $start->addDay();
        }

        return $this->render('timetable', [
            'date'      => $date,
            'today'     => $today,
            'nav'       => $nav,
            'prev'      => $date->copy()->subDays(7),
            'next'      => $date->copy()->addDays(7),
            'timetable' => $timetable,
            'dates'     => $dates
        ]);
    }

    /**
     * Show cart and form for customer to checkout
     *
     * @return View
     */
    public function checkout()
    {
        return $this->render('checkout', $this->getCheckoutData());
    }

    /**
     * Get data passed to Checkout view
     *
     * @return array
     */
    public function getCheckoutData()
    {
        $cart = Cart::findOrFail(Input::get('cartId'));
        $hash = Input::get('hash');
        $user = $this->getUser($hash);

        return [
            'hash'         => Input::get('hash'),
            'cart'         => $cart,
            'booking_info' => $this->getBookingInfo(),
            'user'         => $user
        ];
    }

    /**
     * Validate submitted data
     *
     * @return View|Redirect
     */
    public function confirm()
    {
        $v = $this->getConfirmationValidator();
        if ($v->fails()) {
            // Flash old input
            Input::flash();
            $viewErrorBag = new ViewErrorBag();
            $viewErrorBag->put('errors', $v->errors());

            return $this->render('form', $this->getCheckoutData())
                ->with('errors', $viewErrorBag );
        }

        $data = Input::all();
        // Handle consumer
        $consumer    = Consumer::handleConsumer($data);
        $cart        = Cart::findOrFail(Input::get('cartId'));
        $cart->notes = (!empty($data['notes'])) ? $data['notes'] : '';
        $cart->consumer()->associate($consumer)->save();

        $data['consumer']            = $consumer;
        $data['cart']                = $cart;
        $data['user']                = $this->getUser();
        $data['isRequestedEmployee'] = Input::get('is_requested_employee', false);

        return $this->render('confirm', $data);
    }
}
