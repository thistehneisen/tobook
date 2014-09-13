<?php namespace App\Core\Controllers;

use View, Validator, Input, Redirect, Config, Session, URL;
use User;
use Confide;

class Dashboard extends Base
{
    public function index()
    {
        // Get all modules availables for this user
        $modules = Confide::user()->modules;

        return View::make('dashboard.index', [
            'user'    => Confide::user(),
            'modules' => $modules,
        ]);
    }
}
