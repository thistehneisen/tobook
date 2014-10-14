<?php namespace App\Appointment\Controllers\Embed;

use Input, Response, Carbon\Carbon, Session, Redirect, Payment, Cart;
use App\Appointment\Controllers\Embed;
use App\Appointment\Models\Service;
use App\Appointment\Models\Employee;
use App\Appointment\Models\AsConsumer;

class Layout3 extends Base
{
    /**
     * Show the form for customer to checkout
     *
     * @return View
     */
    public function checkout()
    {
        return $this->render('checkout', [
            'user'         => $this->getUser(),
            'booking_info' => $this->getBookingInfo(),
        ]);
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

            return $this->render('checkout', [
                'user'         => $user,
                'booking_info' => $this->getBookingInfo(),
            ])->with('errors', $v->errors());
        }

        // We will show all information and ask for confirmation
        $data             = Input::all();
        $data['date']     = new Carbon($data['date']);
        $data['service']  = Service::find(Input::get('serviceId'));
        $data['employee'] = Employee::findOrFail(Input::get('employeeId'));
        $data['notes']    = Input::get('notes', '');
        $data['user']     = $user;

        $fields = ['postcode', 'address', 'city', 'country'];
        foreach ($fields as $field) {
            if ((int)$user->asOptions[$field] >= 2) {
                $data['fields'][] = $field;
            }
        }
        // Handle consumer
        $consumer    = AsConsumer::handleConsumer($data);
        $cart        = Cart::findOrFail(Input::get('cartId'));
        $cart->notes = $data['notes'];
        $cart->consumer()->associate($consumer)->save();

        $data['consumer'] = $consumer;

        return $this->render('confirm', $data);
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
        return Payment::redirect($cart, $cart->subtotal, $goToPaygate);
    }
}
