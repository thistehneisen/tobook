<?php namespace App\Appointment\Controllers\Embed;

use App;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Payment\Models\Transaction;
use Carbon\Carbon;
use Cart;
use CheckoutFinland\Client;
use CheckoutFinland\Payment;
use Config;
use Input;
use Redirect;
use Response;
use WebToPay;

class LayoutCp extends Base
{
    use Layout;

    public function getServices()
    {
        $hash = Input::get('hash');
        $data = $this->handleIndex($hash);
        return Response::json([
            'categories'       => $data['categories'],
            'priceRange'       => $data['priceRange'],
            'hasDiscount'      => $data['hasDiscount'],
            'servicesDiscount' => $data['servicesDiscount'],
            'business'         => $data['user']->business,
        ]);
    }

    public function getEmployees()
    {
        $hash = Input::get('hash');
        $user = $this->getUser($hash);

        if (Input::has('serviceTimeId')) {
            $service = ServiceTime::ofUser($user)
                ->find(Input::get('serviceTimeId'))
                ->service;
        } else {
            $service = Service::ofUser($user)
                ->find(Input::get('serviceId'));
        }

        return Response::json($service->employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => $employee->getAvatarUrl(),
            ];
        }));
    }

    public function getTimetable()
    {
        $today      = Carbon::today();
        $date       = Input::has('date') ? new Carbon(Input::get('date')) : $today;
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

        $selectedService = (empty($serviceTime)) ? $service : $serviceTime;

        // Get timetable data
        $timetable   = [];
        $showEndTime = false;
        $discount    = true;

        $employee = null;
        $timetableMethod = 'getTimetableOfAnyone';
        $params = [];

        if ($employeeId > 0) {
            $employee = Employee::findOrFail($employeeId);
            $params[] = $employee;
            $timetableMethod = 'getTimetableOfSingle';
        }

        $params[] = $service;
        $params[] = $date;
        $params[] = $serviceTime;
        $params[] = $showEndTime;
        $params[] = $discount;

        while (empty($timetable)) {
            $timetable = call_user_func_array([$this, $timetableMethod], $params);

            if (empty($timetable)) {
                // Move to the next day until we find bookable slots
                $date->addDay();
            }
        }

        // Calculate timeslots of the whole week just to know which days are
        // unbookable
        $unbookable = [];
        $s = $date->copy()->startOfWeek();
        $e = $s->copy()->endOfWeek();
        while ($s->lte($e)) {
            // Of course we cannot book on past days
            if ($s->lt($today)) {
                $unbookable[] = $s->format('Y-m-d');
                $s->addDay();

                continue;
            }

            if (count($params) === 6) {
                $params[2] = $s;
            } else {
                $params[1] = $s;
            }

            $t = call_user_func_array([$this, $timetableMethod], $params);
            if (empty($t)) {
                $unbookable[] = $s->format('Y-m-d');
            }
            $s->addDay();
        }

        list($start, $final, $maxWeeks) = $this->getMinMaxDistanceDay($hash);

        if ($date->lt($start)) {
            $date = $start->copy();
        }

        $startDate = $date->copy()->startOfWeek();
        $endDate   = $startDate->copy()->endOfWeek();
        $nextWeek  = $endDate->copy()->addDay();
        // Start of the week so that the date won't fall into Sunday which is
        // usually unbookable
        $prevWeek = $startDate->copy()->subDay()->startOfWeek();

        $dates = [];
        $i = $startDate->copy();
        while ($i->lte($endDate)) {
            $dates[] = [
                'dayOfWeek' => trans('common.short.'.strtolower($i->format('D'))),
                'date' => $i->toDateString(),
                'niceDate' => $i->format('j'),
                'hasDiscount' => $selectedService->hasDiscount($i)
            ];
            $i->addDay();
        }

        $calendar = [];
        $dateStr = $date->toDateString();
        foreach ($timetable as $time => $employee) {
            $calendar[] = [
                'time' => $time,
                'date' => $dateStr,
                'discountPrice' => $this->getDiscountPrice($time, $time, $selectedService),
                'price' => $selectedService->price,
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                ]
            ];
        }

        return Response::json([
            'calendar'     => $calendar,
            'dates'        => $dates,
            'nextWeek'     => $nextWeek->toDateString(),
            'prevWeek'     => $prevWeek->toDateString(),
            'selectedDate' => $dateStr,
            'unbookable'   => $unbookable,
        ]);
    }

    public function getPaymentOptions()
    {
        $hash = Input::get('hash');
        $user = $this->getUser($hash);
        if ($user->business->disabled_payment) {
            return Response::json([
                'disabled_payment' => true,
                'url' => route('business.booking.pay_at_venue', ['hash' => $hash])
            ]);
        }

        $result = is_tobook()
            ? $this->getWebToPayOptions()
            : $this->getCheckoutOptions();

        // Add Pay at venue button
        $result['payment_methods'][] = [
            'url' => route('business.booking.pay_at_venue', ['hash' => $hash]),
            'key' => 'pay_at_venue',
            'attr' => [
                'cart_id' => $result['cart_id']
            ],
            'logo' => asset_path('core/img/pay-at-venue.png'),
            'title' => trans('home.cart.pay_venue'),
        ];

        $result['disabled_payment'] = false;
        return Response::json($result);
    }

    public function getCheckoutOptions()
    {
        $id = Config::get('services.checkout.id');
        $secret = Config::get('services.checkout.secret');

        $transaction = $this->getTransaction();
        $transaction->paygate = \Payment::CHECKOUT;
        $transaction->save();

        $payment = new Payment($id, $secret);
        $payment->setReturnUrl(route('payment.notify', ['gateway' => 'checkout']));
        $payment->setCancelUrl(route('payment.cancel', ['id' => $transaction->id]));
        $payment->setDelayedUrl(route('payment.notify', ['gateway' => 'checkout']));
        $payment->setRejectUrl(route('payment.notify', ['gateway' => 'checkout']));
        $payment->setData([
            'stamp'        => time(),
            'amount'       => round(Input::get('amount', 10), 2) * 100,
            'message'      => '',
            'country'      => 'FIN',
            'language'     => strtoupper(App::getLocale()),
            'reference'    => $transaction->id,
            'deliveryDate' => Carbon::today(),
        ]);

        $client = new Client();
        $response = $client->sendPayment($payment);
        $methods = [];
        if ($response) {
            $xml = @simplexml_load_string($response);
            if ($xml and isset($xml->id)) {
                foreach ($xml->payments->payment->banks as $banks) {
                    foreach ($banks as $key => $bank) {
                        $data = [
                            'key' => $key,
                            'url' => (string) $bank['url'],
                            'logo' => (string) $bank['icon'],
                            'title' => (string) $bank['name'],
                        ];
                        // Extract hidden values
                        foreach ($bank as $k => $v) {
                            $data['attr'][$k] = (string) $v;
                        }

                        $methods[] = $data;
                    }
                }
            }
        }

        return [
            'cart_id' => $transaction->cart !== null ? $transaction->cart->id : null,
            'transaction' => $transaction->id,
            'payment_methods' => $methods,
        ];
    }

    /**
     * Find or create a transaction for this session
     * Update the amount in case user chooses another service
     *
     * @return App\Payment\Models\Transaction
     */
    public function getTransaction()
    {
        $transaction = Transaction::find(Input::get('transaction_id'));
        $amount = round(Input::get('amount', 10), 2);
        if ($transaction === null) {
            $transaction = new Transaction();

            $cart = Cart::find(Input::get('cart_id'));
            if ($cart !== null) {
                $transaction->cart()->associate($cart);
            }
        }
        $transaction->amount = $amount;
        $transaction->save();

        return $transaction;
    }

    /**
     * Get payment options from WebToPay
     *
     * @return array
     */
    public function getWebToPayOptions()
    {
        // $language = App::getLocale() ?: 'en';
        $language = 'lt';
        $amount = Input::get('amount', 10.00);
        $options = WebToPay::getPaymentMethodList(Config::get('services.paysera.id'), 'EUR')
            ->filterForAmount($amount, 'EUR')
            ->setDefaultLanguage($language);

        $methods = [];
        if ($options->getCountry($language) !== null) {
            foreach ($options->getCountry($language)->getPaymentMethods() as $method) {
                $methods[] = [
                    'key' => $method->getKey(),
                    'logo' => $method->getLogoUrl(),
                    'title' => $method->getTitle()
                ];
            }
        }
        return $methods;
    }

    public function payAtVenue()
    {
        $cart = Cart::findOrFail(Input::get('cart_id'));
        $result = $cart->completePayAtVenue();

        return Redirect::route('payment.success', ['id' => $cart->id]);
    }
}
