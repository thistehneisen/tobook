<?php namespace App\Controllers;

use View, Validator, Input, Redirect, Config, Session, URL;
use User;
use Confide;

class Dashboard extends Base
{
    public function index()
    {
        $services = [
            'site'        => '',
            'gallery'     => '',
            'profile'     => route('user.profile'),
            'promotion'   => '',
            'cashier'     => URL::route('cashier.index'),
            'restaurant'  => '',
            'timeslot'    => '',
            'appointment' => '',
            'loyalty'     => '',
            'martketing'  => ''
        ];

        return View::make('dashboard.index', [
            'user' => Confide::user(),
            'services' => $services
        ]);
    }
}
