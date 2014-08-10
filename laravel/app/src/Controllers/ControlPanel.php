<?php namespace App\Controllers;

use View, Validator, Input, Redirect, Config, Session;
use User;
use Confide;

class ControlPanel extends Base
{
    public function index()
    {
        return View::make('cpanel.index', [
            'user' => Confide::user()
        ]);
    }
}
