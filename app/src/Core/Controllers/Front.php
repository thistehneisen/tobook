<?php namespace App\Core\Controllers;

use View, Confide, Redirect;

class Front extends Base
{
    public function home()
    {
        if (Confide::user()) {
            return Redirect::route('dashboard.index');
        }
        return View::make('front.home');
    }

    // for business
    public function businessIndex()
    {
        return View::make('front.business.index');
    }

    public function businessWebsiteList()
    {
        return View::make('front.business.list');
    }

    public function businessLoyalty()
    {
        return View::make('front.business.loyalty');
    }

    public function businessOnlineBooking()
    {
        return View::make('front.business.onlinebooking');
    }

    public function businessCashier()
    {
        return View::make('front.business.cashier');
    }

    public function businessMarketingTools()
    {
        return View::make('front.business.marketing');
    }
}
