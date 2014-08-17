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

    public function websiteList() {
        return View::make('intro.list');
    }

    public function loyalty() {
        return View::make('intro.loyalty');
    }

    public function timeslot() {
        return View::make('intro.timeslot');
    }

    public function cashier() {
        return View::make('intro.cashier');
    }

    public function marketingTools() {
        return View::make('intro.marketing');
    }
}
