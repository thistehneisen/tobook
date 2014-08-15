<?php namespace App\Controllers;

use View;
use Confide;

class Home extends Base
{
    public function index()
    {
        if (Confide::user()) {
            return View::make('home.list');
        }

        return View::make('home.index');
    }

    public function loyalty() {
        return View::make('intro.loyalty');
    }
}
