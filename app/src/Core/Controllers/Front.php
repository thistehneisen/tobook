<?php namespace App\Core\Controllers;

use View, Confide, Redirect, Config, Input, Response, Settings;
use Carbon\Carbon;
use App\Core\Models\Business;
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

        // get 4 random businesses of selected category
        $businesses = Business::getRandomBusinesses($categoryId, 4);

        return View::make('front.home', [
            'deals'          => $deals,
            'categories'     => $categories,
            'categoryId'     => $categoryId,
            'businesses'     => $businesses,
            'now'            => Carbon::now()
        ]);
    }

    public function robots()
    {
        $str = "User-agent: *\n";
        $str .= "Disallow: ";

        if (!Settings::get('allow_robots')) {
            $str .= "/";
        }

        $response = Response::make($str, 200)->header('Content-Type', 'text/plain');
        return $response;
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
}
