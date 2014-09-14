<?php namespace App\Core\Controllers;

use View, Confide, Redirect, Config;
use App\Core\Models\BusinessCategory;
use App\FlashDeal\Models\FlashDealDate;

class Front extends Base
{
    public function home()
    {
        $deals = $this->getFlashDeals();
        return View::make('front.home', [
            'deals' => $deals
        ]);
    }

    protected function getFlashDeals()
    {
        // Which categories?
        $categories = BusinessCategory::whereIn(
            'id',
            Config::get('varaa.flash_deal.categories')
        )->with('children')->get();

        foreach ($categories as &$category) {
            $category->deals = FlashDealDate::ofBusinessCategory(
                    $category->children->lists('id')
                )
                ->active()
                ->orderBy('expire', 'ASC')
                ->take(Config::get('varaa.flash_deal.limit'))
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
