<?php namespace App\Core\Controllers;

use View, Confide, Redirect, Config, Input;
use Carbon\Carbon;
use App\Core\Models\User;
use App\Core\Models\BusinessCategory;
use App\FlashDeal\Models\FlashDealDate;

class Front extends Base
{
    public function home()
    {
        $deals = $this->getFlashDeals();
        $categoryId = Input::get('category', 0);
        $categories = BusinessCategory::lists('name', 'id');

        //rewind array pointer to the beginning
        reset($categories);
        $categoryId = (!empty($categoryId))
            ? $categoryId
            : key($categories);

        $nextAvailables = $this->getNextAvailables($categoryId);

        return View::make('front.home', [
            'deals'          => $deals,
            'categories'     => $categories,
            'categoryId'     => $categoryId,
            'nextAvailables' => $nextAvailables
        ]);
    }

    /**
     * Get all available flash deals in the system
     *
     * @return array
     */
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

    protected function getNextAvailables($categoryId)
    {
        $now = Carbon::now();
        //Get 4 random users of selected business category
        $users = User::getRandomUser($categoryId, 4);
        $nextAvailables = [];
        foreach ($users as $user) {
            $results = $user->getASNextTimeSlots($now->toDateString(), $now->hour);
            $nextAvailables[$user->id] = $results;
        }
        return $nextAvailables;
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
