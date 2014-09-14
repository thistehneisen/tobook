<?php namespace App\Core\Controllers;

use View, Confide, Redirect, Config;
use App\Core\Models\BusinessCategory;
use App\FlashDeal\Models\FlashDealDate;

class Front extends Base
{
    public function home()
    {
        if (Confide::user()) {
            return Redirect::route('dashboard.index');
        }

        $deals = $this->getFlashDeals();
        return View::make('front.home', [
            'deals' => $deals
        ]);
    }

    protected function getFlashDeals()
    {
        // Which categories?
        $categories = BusinessCategory::whereIn('id', Config::get('varaa.flash_deal_categories'))->get();
        foreach ($categories as &$category) {
            $category->deals = FlashDealDate::ofBusinessCategory($category->id)
                ->active()
                ->take(8)
                ->get();
        }
        return $categories;
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
