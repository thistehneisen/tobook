<?php namespace App\Appointment\Controllers\Embed;

use Hashids, Input, View, Session, Redirect, URL, Config, Validator, Event, App;
use Cart;
use Carbon\Carbon;
use App\Core\Models\User;
use App\Consumers\Models\Consumer;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;

class Layout1 extends Base
{
    public function addConfirmInfo()
    {
        $data                = Input::all();
        $hash                = Input::get('hash');
        $cartId              = Input::get('cart_id');
        $notes               = Input::get('notes');
        $user                = $this->getUser($hash);
        $isRequestedEmployee = Input::get('is_requested_employee', false);

        $validation = $this->getConfirmationValidator();
        if ( $validation->fails() ) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $consumer    = Consumer::handleConsumer($data);
        $cart        = Cart::find($cartId);
        $cart->notes = $notes;
        $cart->consumer()->associate($consumer)->save();

        return Redirect::route('as.embed.embed', [
            'hash'                  => $hash,
            'action'                => 'confirm',
            'user'                  => $user,
            'cart_id'               => $cartId,
            'is_requested_employee' => $isRequestedEmployee,
        ]);
    }

    /**
     * Display the adding extra service form
     *
     * @return View
     */
    public function getExtraServiceForm()
    {
        $service       = Service::findOrFail(Input::get('service_id'));
        $serviceTime   = Input::get('service_time');
        $extraServices = $service->extraServices()->where('is_hidden', '=', 'false')->get();

        return $this->render('extraServices', [
            'date'          => Input::get('date') ?: Carbon::now()->toDateString(),
            'extraServices' => $extraServices,
            'service'       => $service,
            'serviceTime'   => $serviceTime,
        ]);
    }
}
