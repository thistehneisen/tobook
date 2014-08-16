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
        $params['module'] = (Confide::user()->isServiceActivated('sma_', 'users'))
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
        $uri = (Confide::user()->isServiceActivated('ts_', 'calendars'))
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
        $uri = (Confide::user()->isServiceActivated('rb_', 'users'))
            ? 'restaurant/session.php?'
            : 'restaurant/install.php?';

        return View::make('services.iframe', [
            'url' => URL::to($uri.http_build_query($params))
        ]);
    }

    /**
     * Redirect user to Appointment Scheduler module
     *
     * @return View
     */
    public function appointment()
    {
        $params = [
            'username' => Confide::user()->username,
            'owner_id' => Confide::user()->id
        ];

        $uri = (Confide::user()->isServiceActivated('as_', 'users'))
            ? 'appointment/index.php?'
            : 'appointment/install.php?';

        return View::make('services.iframe', [
            'url' => URL::to($uri.http_build_query($params))
        ]);
    }

    /**
     * Redirect user to Loyalty Program module
     *
     * @return View
     */
    public function loyalty()
    {
        $params = [
            'username' => Confide::user()->username,
            'userid' => Confide::user()->id
        ];
        return View::make('services.iframe', [
            'url' => URL::to(
                'loyalty/admin/consumerList.php?'
                .http_build_query($params)
            )
        ]);
    }

    /**
     * Redirect user to Marketing Tool module
     *
     * @return View
     */
    public function marketing()
    {
        $params = [
            'username' => Confide::user()->username,
            'userid' => Confide::user()->id
        ];
        return View::make('services.iframe', [
            'url' => URL::to(
                'marketing/main.php?'
                .http_build_query($params)
            )
        ]);
    }
}
