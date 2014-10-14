<?php namespace App\Appointment\Controllers\Embed;

use Hashids, Input, View, Session, Redirect, URL, Config, Validator, Event, App;
use Cart;
use Carbon\Carbon;
use App\Core\Models\User;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\AsConsumer;
use App\Consumers\Models\Consumer;

class Layout1 extends Base
{
    public function addConfirmInfo()
    {
        $data   = Input::all();
        $hash   = Input::get('hash');
        $cartId = Input::get('cart_id');
        $notes  = Input::get('notes');

        $validation = $this->getConfirmationValidator();
        if ( $validation->fails() ) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $consumer    = AsConsumer::handleConsumer($data);
        $cart        = Cart::find($cartId);
        $cart->notes = $notes;
        $cart->consumer()->associate($consumer)->save();

        return Redirect::route('as.embed.embed', ['hash' => $hash, 'action'=> 'confirm', 'cart_id' => $cartId]);
    }

    /**
     * Display the adding extra service form
     *
     * @return View
     */
    public function getExtraServiceForm()
    {
        $service = Service::findOrFail(Input::get('service_id'));
        $serviceTime = Input::get('service_time');

        return $this->render('extraServices', [
            'date'        => Input::get('date') ?: Carbon::now()->toDateString(),
            'service'     => $service,
            'serviceTime' => $serviceTime,
        ]);
    }
}
