<?php namespace App\Core\Controllers;

use View, Confide, Redirect, Config, Input, Response, Settings;
use Carbon\Carbon;
use App\Core\Models\Business;
use App\Core\Models\BusinessCategory;
use App\FlashDeal\Models\FlashDealDate;
use Illuminate\Support\Collection;

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
        $deals = $this->getDeals();

        // Because of the layout, we need to split deals into smaller parts
        $firstDeals = $deals->splice(0, 4);
        $restDeals = $deals->splice(4);

        return $this->render('home', [
            'categories' => $categories,
            'deals'      => $deals,
            'firstDeals' => $firstDeals,
            'restDeals'  => $restDeals,
        ]);
    }

    /**
     * Get active deals in database
     *
     * @return Illuminate\Support\Collection
     */
    public function getDeals()
    {
        $deals = FlashDealDate::active()
            ->distinct()
            ->orderBy('expire')
            ->with('flashDeal')
            ->take(50) // @TODO: Remove hard-code
            ->get();

        $deals->each(function (&$item) {
            // @TODO: Slow!!!
            $item = $item->attachBusiness($item->user->business);
        });

        return $deals;
    }

    /**
     * Show the list of all businesses in the site
     *
     * @return View
     */
    public function businesses()
    {
        // Get all businesses
        $businesses = Business::with('user.images')->paginate();

        // Get deals from businesses
        $deals = $this->getDealsOfBusinesses($businesses);

        return $this->render('businesses', [
            'businesses' => $businesses,
            'pagination' => $businesses->links(),
            'deals'      => $deals,
            'heading'    => trans('home.businesses'),
        ]);
    }

    /**
     * Show businesses of a category
     *
     * @param  int $categoryId
     * @param  string $slug
     *
     * @return View
     */
    public function category($categoryId, $slug)
    {
        $category = BusinessCategory::findOrFail($categoryId);
        $businesses = $category->users()->has('business')->paginate();

        $deals = $this->getDealsOfBusinesses($businesses);
        return $this->render('businesses', [
            'businesses' => $businesses->lists('business'),
            'pagination' => $businesses->links(),
            'deals'      => $deals,
            'heading'    => trans('home.businesses_category', ['category' => $category->name]),
        ]);
    }

    /**
     * Get active deals of provided businesses
     *
     * @param  Illuminate\Support\Collection $businesses
     *
     * @return Illuminate\Support\Collection
     */
    protected function getDealsOfBusinesses($businesses)
    {
        $deals = new Collection();
        foreach ($businesses as $business) {
            $items = FlashDealDate::active()
                ->ofBusiness($business)
                ->with(['flashDeal', 'flashDeal.service'])
                ->orderBy('expire')
                ->get();
            $items->each(function (&$item) use ($business) {
                $item = $item->attachBusiness($business);
            });

            $deals = $deals->merge($items);
        }
        return $deals;
    }

    /**
     * Dynamically create robots.txt file based on user setting
     *
     * @return View
     */
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
}
