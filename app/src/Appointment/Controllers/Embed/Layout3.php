<?php namespace App\Appointment\Controllers\Embed;

use Input, Response, Carbon\Carbon, Session, Redirect;
use App\Appointment\Controllers\Embed;
use App\Appointment\Models\Service;
use App\Appointment\Models\Employee;

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
