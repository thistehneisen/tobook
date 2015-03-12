<?php namespace App\Core\Controllers;

use App\Core\Models\Business;
use App\Core\Models\BusinessCategory;
use App\FlashDeal\Models\FlashDeal;
use Illuminate\Support\Collection;
use Input;
use Response;
use Session;
use Settings;
use Util;

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
        $deals = FlashDeal::getActiveDeals();

        // Count number of active deals
        $totalDeals = $deals->count();

        // Extract deal categories and its counters
        $dealCategories = FlashDeal::getDealCategories($deals);

        // Because of the layout, we need to split deals into smaller parts
        $head = $deals->splice(0, 4);

        return $this->render('home', [
            'categories'     => $categories,
            'head'           => $head,
            'tail'           => $deals,
            'totalDeals'     => $totalDeals,
            'dealCategories' => $dealCategories,
        ]);
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

        // Get lat and lng to show the map
        $lat = Session::get('lat');
        $lng = Session::get('lng');
        if (empty($lat) && empty($lng)) {
            try {
                list($lat, $lng) = Util::geocoder(
                    Settings::get('default_location')
                );
            } catch (\Exception $ex) { /* Silently failed */ }
        }

        return $this->render('businesses', [
            'businesses' => $businesses,
            'pagination' => $businesses->links(),
            'deals'      => $deals,
            'lat'        => $lat,
            'lng'        => $lng,
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
            $items = FlashDeal::ofBusiness($business)
                ->whereHas('dates', function ($query){
                    return $query->active()->orderBy('expire');
                })
                ->with('user.business')
                ->get();

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
