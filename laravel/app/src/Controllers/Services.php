<?php namespace App\Controllers;

use View, URL, Confide, Redirect;

class Services extends Base
{
    public function __construct()
    {
        // @todo: Check membership. It's better to have a filter and attach it
        // to this route
    }

    public function cashier()
    {
        $params = ['prefix' => Confide::user()->username];

        // Check if this user has been activated Cashier module
        $params['module'] = (Confide::user()->isCashierActivated())
            ? 'home'
            : 'seed';

        return View::make('services.iframe', [
            'url' => URL::to('cashier/index.php?'.http_build_query($params))
        ]);
    }

    /**
     * Redirect user to TimeSlot module
     *
     * @return View
     */
    public function timeslot()
    {
        $params = ['username' => Confide::user()->username];
        $uri = (Confide::user()->isTimeSlotActivated())
            ? 'timeslot/session.php?'
            : 'timeslot/install.php?';

        //return Redirect::to($uri.http_build_query($params));
        return View::make('services.iframe', [
            'url' => URL::to($uri.http_build_query($params))
        ]);
    }

    /**
     * Redirect user to Restaurant Booking module
     *
     * @return View
     */
    public function restaurant()
    {
        $params = [
            'username' => Confide::user()->username,
            'owner_id' => Confide::user()->id
        ];
        $uri = (Confide::user()->isRestaurantBookingInstalled())
            ? 'restaurant/session.php?'
            : 'restaurant/install.php?';

        return View::make('services.iframe', [
            'url' => URL::to($uri.http_build_query($params))
        ]);
    }
}
