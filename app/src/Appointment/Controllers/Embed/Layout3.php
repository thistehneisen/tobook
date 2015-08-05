<?php namespace App\Appointment\Controllers\Embed;

use Input, Response, Carbon\Carbon, Session, Redirect, Payment, Cart;
use App, Settings;
use App\Appointment\Controllers\Embed;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\Employee;
use App\Consumers\Models\Consumer;
use Illuminate\Support\ViewErrorBag;

class Layout3 extends Base
{
    /**
     * Show the form for customer to checkout
     *
     * @return View
     */
    public function checkout()
    {
        $data = [
            'user'         => $this->getUser(),
            'booking_info' => $this->getBookingInfo(),
        ];

        $tpl = 'checkout-plain';
        if ((bool) Input::get('inhouse')) {
            $data = $this->getConfirmationData();
            // Show the form to add to cart
            $tpl = 'cart';
        }

        $data['terms'] = Settings::getBookingTerms(App::getLocale());

        return $this->render($tpl, $data);
    }

    /**
     * Validate submitted data
     *
     * @return View
     */
    public function confirm()
    {
        $v    = $this->getConfirmationValidator();
        $user = $this->getUser();
        if ($v->fails()) {
            // Flash old input
            Input::flash();
            $viewErrorBag = new ViewErrorBag;
            $viewErrorBag->put('errors', $v->errors());
            return $this->render('checkout', [
                'user'         => $user,
                'booking_info' => $this->getBookingInfo(),
                'layout'       => $this->getLayout(),
                'hash'         => Input::get('hash')
            ])->with('errors', $viewErrorBag);
        }

        // We will show all information and ask for confirmation
        $data = $this->getConfirmationData();

        $fields = ['postcode', 'address', 'city', 'country'];
        foreach ($fields as $field) {
            if ((int)$user->asOptions[$field] >= 2) {
                $data['fields'][] = $field;
            }
        }
        // Handle consumer
        $consumer    = Consumer::handleConsumer($data);
        $cart        = Cart::findOrFail(Input::get('cartId'));
        $cart->notes = $data['notes'];
        $cart->consumer()->associate($consumer)->save();

        $data['consumer'] = $consumer;
        $data['user']     = $user;
        $data['layout']   = $this->getLayout();

        $tpl = 'confirm';
        if ((bool) Input::get('inhouse')) {
            $tpl = 'confirm-plain';
        }
        return $this->render($tpl, $data);
    }

    protected function getConfirmationData()
    {
        $data                        = Input::all();
        $data['date']                = new Carbon($data['date']);
        $data['service']             = Service::find(Input::get('serviceId'));
        $data['serviceTime']         = (Input::get('serviceTimeId')) ? ServiceTime::find(Input::get('serviceTimeId')) : null;
        $data['employee']            = Employee::findOrFail(Input::get('employeeId'));
        $data['notes']               = Input::get('notes', '');
        $data['isRequestedEmployee'] = Input::get('is_requested_employee', false);
        return $data;
    }

    /**
     * Process to payment page
     *
     * @return Redirect
     */
    public function payment()
    {
        $cart = Cart::findOrFail(Input::get('cart_id'));
        $goToPaygate = true;
        return Payment::redirect($cart, $cart->total, $goToPaygate);
    }
}
