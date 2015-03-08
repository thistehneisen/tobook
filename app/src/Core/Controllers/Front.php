<?php namespace App\Core\Controllers;

use View, Confide, Redirect, Config, Input, Response, Settings;
use Carbon\Carbon;
use App\Core\Models\Business;
use App\Core\Models\BusinessCategory;
use App\FlashDeal\Models\FlashDealDate;

class Front extends Base
{
    protected $viewPath = 'front';

    /**
     * Front page of the site
     *
     * @return View
     */
    public function home()
    {
        $categories = BusinessCategory::getAll();

        return $this->render('home', [
            'categories' => $categories,
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
