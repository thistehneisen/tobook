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
            'cashier'     => route('cashier.index'),
            'restaurant'  => '',
            'timeslot'    => route('timeslot.index'),
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
