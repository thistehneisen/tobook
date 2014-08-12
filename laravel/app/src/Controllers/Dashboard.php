<?php namespace App\Controllers;

use View, Validator, Input, Redirect, Config, Session;
use User;
use Confide;

class Dashboard extends Base
{
    public function index()
    {
        return View::make('dashboard.index', [
            'user' => Confide::user()
        ]);
    }
}
