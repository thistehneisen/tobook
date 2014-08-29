<?php namespace App\Core\Controllers;

use View;
use Confide;

class Home extends Base
{
    public function index()
    {
        return View::make('home.index');
    }

    public function websiteList()
    {
        return View::make('intro.list');
    }

    public function loyalty()
    {
        return View::make('intro.loyalty');
    }

    public function onlineBooking()
    {
        return View::make('intro.onlinebooking');
    }

    public function cashier()
    {
        return View::make('intro.cashier');
    }

    public function marketingTools()
    {
        return View::make('intro.marketing');
    }
}
