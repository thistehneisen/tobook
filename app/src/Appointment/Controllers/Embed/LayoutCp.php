<?php namespace App\Appointment\Controllers\Embed;

use App;
use App\Payment\Models\Transaction;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use Carbon\Carbon;
use CheckoutFinland\Client;
use CheckoutFinland\Payment;
use Config;
use Input;
use Response;
use WebToPay;

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
        $today      = Carbon::today();
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

    public function getPaymentOptions()
    {
        $result = is_tobook()
            ? $this->getWebToPayOptions()
            : $this->getCheckoutOptions();

        // Add Pay at venue button
        $result['payment_methods'][] = [
            'key' => 'pay_at_venue',
            'logo' => 'http://i.imgur.com/uh8LWm2.png',
            'title' => trans('home.cart.pay_venue'),
        ];

        return Response::json($result);
    }

    public function getCheckoutOptions()
    {
        $id = Config::get('services.checkout.id');
        $secret = Config::get('services.checkout.secret');

        $transaction = $this->getTransaction();

        $payment = new Payment($id, $secret);
        $payment->setData([
            'stamp'        => time(),
            'amount'       => round(Input::get('amount', 10), 2) * 100,
            'message'      => '',
            'country'      => 'FIN',
            'language'     => strtoupper(App::getLocale()),
            'reference'    => $transaction->id, // TODO: Fill real data
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
                        $methods[] = [
                            'key' => $key,
                            'logo' => (string) $bank['icon'],
                            'title' => (string) $bank['name'],
                        ];
                    }
                }
            }
        }

        return [
            'transaction_id' => $transaction->id,
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
}
