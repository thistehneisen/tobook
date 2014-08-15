<?php namespace App\Controllers;

use View, Validator, Input, Redirect, Config, Session, URL;
use User;
use Confide;

class Dashboard extends Base
{
    public function index()
    {
        $services = [
            //'site'        => '',
            //'gallery'     => '',
            //'profile'     => route('user.profile'),
            //'promotion'   => '',
            'cashier'     => route('cashier.index'),
            'restaurant'  => route('restaurant.index'),
            'timeslot'    => route('timeslot.index'),
            'appointment' => route('appointment.index'),
            'loyalty'     => route('loyalty.index'),
            //'martketing'  => route('marketing.index'),
        ];

        return View::make('dashboard.index', [
            'user' => Confide::user(),
            'services' => $services
        ]);
    }
}
