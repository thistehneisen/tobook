<?php namespace App\Appointment\Controllers\Embed;

use Input, Response, Carbon\Carbon, Session, Redirect;
use App\Appointment\Models\Service;
use App\Appointment\Models\Employee;
use App\Appointment\Models\AsConsumer;
use App\Core\Models\Cart;

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

        $serviceTime = null;
        if (Input::has('serviceTimeId')) {
            $serviceTime = $service->serviceTimes()
                ->findOrFail(Input::get('serviceTimeId'));
        }

        // Calculate date ranges for nav
        $nav = [];
        $i = 1;
        $start = $today->copy();
        while ($i++ <= 5) {
            $end = $start->copy()->addDays(4);
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
        while ($i++ <= 5) {
            if ($employee !== null) {
                $time = $this->getTimetableOfSingle($employee, $service, $start, $serviceTime, true);
            } else {
                $time = $this->getTimetableOfAnyone($service, $start, $serviceTime, true);
            }

            $dates[]     = $start->copy();
            $timetable[] = (object) [
                'date' => $start->copy(),
                'time' => $time
            ];

            $start = $start->addDay();
        }

        return $this->render('timetable', [
            'date'      => $date,
            'nav'       => $nav,
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

            return $this->render('form', $this->getCheckoutData())
                ->with('errors', $v->errors());
        }


        $data = Input::all();
        // Handle consumer
        $consumer    = AsConsumer::handleConsumer($data);
        $cart        = Cart::findOrFail(Input::get('cartId'));
        $cart->notes = $data['notes'];
        $cart->consumer()->associate($consumer)->save();

        $data['consumer'] = $consumer;
        $data['cart']     = $cart;
        $data['user']     = $this->getUser();

        return $this->render('confirm', $data);
    }
}
