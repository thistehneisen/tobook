<?php namespace App\Core\Controllers;

use View, Confide, Response, Input;
use App\Core\Models\BusinessCategory;

class Front extends Base
{
    public function home()
    {
        return View::make('front.home');
    }

    public function ajaxGetCategories()
    {
        $categories = BusinessCategory::getAll();
        $result = [];
        foreach ($categories as $cat) {
            $result[] = $cat->name;
            if ($cat->keywords !== '') {
                $result = array_merge($result, array_map('trim', explode(',', $cat->keywords)));
            }
            foreach ($cat->children as $subCat) {
                $result[] = $subCat->name;
                if ($subCat->keywords !== '') {
                    $result = array_merge($result, array_map('trim', explode(',', $subCat->keywords)));
                }
            }
        }
        return Response::json($result, 200);
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
