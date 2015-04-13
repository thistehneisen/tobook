<?php namespace App\Core\Controllers;

use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\TreatmentType;
use App\Core\Models\Business;
use App\Core\Models\BusinessCategory;
use App\Core\Models\User;
use App\FlashDeal\Models\FlashDeal;
use Illuminate\Support\Collection;
use Request;
use Response;
use Session;
use Settings;
use Util;

class Front extends Base
{
    protected $viewPath = 'front';

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

        // Master categories
        $masterCategories = MasterCategory::getAll();

        return $this->render('home', [
            'categories'       => $categories,
            'head'             => $head,
            'tail'             => $deals,
            'totalDeals'       => $totalDeals,
            'dealCategories'   => $dealCategories,
            'masterCategories' => $masterCategories,
        ]);
    }

    /**
     * Show businesses in a master category
     *
     * @param int    $id   Master category's ID
     * @param string $slug
     *
     * @return View
     */
    public function masterCategory($id, $slug = null)
    {
        $category = MasterCategory::findOrFail($id);

        $paginator = User::with('business')
            ->whereHas('asServices', function ($q) use ($id) {
                $q->where('master_category_id', $id);
            })
            ->simplePaginate();

        // Extract list of businesses
        $items = $paginator->getCollection()->lists('business');
        $heading = $category->name;

        // Add meta data to this page
        $meta['description'] = $category->description;

        return $this->renderBusinesses($paginator, $items, $heading, $meta);
    }

    /**
     * Show businesses in a treatment type
     *
     * @param int    $id
     * @param string $slug
     *
     * @return Response|View
     */
    public function treatment($id, $slug = null)
    {
        $treatment = TreatmentType::findOrFail($id);
        $paginator = User::with('business')
            ->whereHas('asServices', function ($q) use ($id) {
                $q->where('treatment_type_id', $id);
            })
            ->simplePaginate();

        // Extract list of businesses
        $items = $paginator->getCollection()->lists('business');
        $heading = $treatment->name;

        // Add meta data to this page
        $meta['description'] = $treatment->description;

        return $this->renderBusinesses($paginator, $items, $heading, $meta);
    }

    /**
     * Show the list of all businesses in the site
     *
     * @return View
     */
    public function businesses()
    {
        // Get all businesses
        $paginator = Business::notHidden()
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with('user.images')
            ->simplePaginate();

        $heading = trans('home.businesses');

        return $this->renderBusinesses($paginator, $paginator->getItems(), $heading);
    }

    /**
     * Auxilary method to render the list of businesses, AJAX pagination supported
     *
     * @param Illuminate\Pagination\Paginator $paginator  The paginator containing
     *                                                    list of businesses
     * @param array                           $businesses The list of businesses extrated from paginator
     * @param string                          $heading    The heading used in the page
     *
     * @return Response|View
     */
    protected function renderBusinesses($paginator, $businesses, $heading, $meta = [])
    {
        // Calculate next page
        $nextPageUrl = $this->getNextPageUrl($paginator);

        $viewData = [
            'businesses' => $businesses,
            'nextPageUrl' => $nextPageUrl,
        ];

        // If this is a Show more request, return the view only
        if (Request::ajax()) {
            return Response::json([
                'businesses' => $businesses,
                'html'       => $this->render('el.sidebar', $viewData)->render()
            ]);
        }

        // Get deals from businesses
        $deals = $this->getDealsOfBusinesses($paginator);

        // Get lat and lng to show the map
        list($lat, $lng) = $this->extractLatLng();

        $viewData['deals']   = $deals;
        $viewData['lat']     = $lat;
        $viewData['lng']     = $lng;
        $viewData['heading'] = $heading;
        $viewData['meta']    = (array) $meta;

        return $this->render('businesses', $viewData);
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
            // dd(Settings::get('default_location'));
                list($lat, $lng) = Util::geocoder(Settings::get('default_location'));
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
        $heading = trans('home.businesses_category', ['category' => $category->name]);

        return $this->renderBusinesses($businesses, $items, $heading);
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
}
