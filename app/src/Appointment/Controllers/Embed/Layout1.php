<?php namespace App\Appointment\Controllers\Embed;

use Hashids, Input, View, Session, Redirect, URL, Config, Validator, Event, App;
use Carbon\Carbon;
use App\Core\Models\User;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;

class Layout1 extends Base
{
    public function addConfirmInfo()
    {
        $data = Input::all();
        $hash = Input::get('hash');

        $validation = $this->getConfirmationValidator();
        if ( $validation->fails() ) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        //TODO probably validate user info here
        Session::put('booking_info', $data);

        return Redirect::route('as.embed.embed', ['hash' => $hash, 'action'=> 'confirm']);
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

        return $this->render($this->getLayout().'.extraServices', [
            'date'        => Input::get('date') ?: Carbon::now()->toDateString(),
            'service'     => $service,
            'serviceTime' => $serviceTime,
        ]);
    }
}
