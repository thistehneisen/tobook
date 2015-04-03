<?php namespace App\Core\Controllers;

use App\Core\Models\Business;
use App\Core\Models\BusinessCategory;
use App\FlashDeal\Models\FlashDeal;
use Illuminate\Support\Collection;
use Response;
use Request;
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
        $businesses = Business::notHidden()
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with('user.images')
            ->simplePaginate();

        // Calculate next page
        $nextPageUrl = $this->getNextPageUrl($businesses);

        $view = [
            'businesses' => $businesses->getItems(),
            'nextPageUrl' => $nextPageUrl,
        ];

        // If this is a Show more request, return the view only
        if (Request::ajax()) {
            return Response::json([
                'businesses' => $businesses->getItems(),
                'html'       => $this->render('el.sidebar', $view)->render()
            ]);
        }

        // Get deals from businesses
        $deals = $this->getDealsOfBusinesses($businesses);

        // Get lat and lng to show the map
        list($lat, $lng) = $this->extractLatLng();

        $view['deals']   = $deals;
        $view['lat']     = $lat;
        $view['lng']     = $lng;
        $view['heading'] = trans('home.businesses');

        return $this->render('businesses', $view);
    }

    /**
     * Get URL for the next page in pagination
     *
     * @param Illuminate\Pagination\Paginator $businesses
     *
     * @return string
     */
    protected function getNextPageUrl($businesses)
    {
        $current = $businesses->getCurrentPage();
        $lastPage = $businesses->getLastPage();
        if ($current + 1 <= $lastPage) {
            return \URL::to($businesses->getUrl($current + 1));
        }

        return '';
    }

    /**
     * Extract lat/lng values from Session and system settings
     *
     * @return array
     */
    protected function extractLatLng()
    {
        $lat = Session::get('lat');
        $lng = Session::get('lng');
        if (empty($lat) && empty($lng)) {
            try {
                list($lat, $lng) = Util::geocoder(
                    Settings::get('default_location')
                );
            } catch (\Exception $ex) { /* Silently failed */ }
        }

        return [$lat, $lng];
    }

    /**
     * Show businesses of a category
     *
     * @param int    $categoryId
     * @param string $slug
     *
     * @return View
     */
    public function category($categoryId, $slug)
    {
        $category = BusinessCategory::findOrFail($categoryId);
        $businesses = $category->users()
            ->whereNull('deleted_at')
            ->whereHas('business', function ($query) {
                $query->notHidden();
            })
            ->paginate();

        $items = $businesses->lists('business');
        // Calculate next page
        $nextPageUrl = $this->getNextPageUrl($businesses);

        // Data for view
        $view = [
            'businesses' => $items,
            'nextPageUrl' => $nextPageUrl,
        ];

        // If this is a Show more request, return the view only
        if (Request::ajax()) {
            return Response::json([
                'businesses' => $items,
                'html'       => $this->render('el.sidebar', $view)->render()
            ]);
        }

        $deals = $this->getDealsOfBusinesses($businesses);

        list($lat, $lng) = $this->extractLatLng();

        $view['deals']       = $deals;
        $view['lat']         = $lat;
        $view['lng']         = $lng;
        $view['heading']     = trans('home.businesses_category', ['category' => $category->name]);

        return $this->render('businesses', $view);
    }

    /**
     * Get active deals of provided businesses
     *
     * @param Illuminate\Support\Collection $businesses
     *
     * @return Illuminate\Support\Collection
     */
    protected function getDealsOfBusinesses($businesses)
    {
        $deals = new Collection();
        foreach ($businesses as $business) {
            $items = FlashDeal::ofBusiness($business)
                ->whereHas('dates', function ($query) {
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
