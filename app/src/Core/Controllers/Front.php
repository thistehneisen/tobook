<?php namespace App\Core\Controllers;

use App;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\TreatmentType;
use App\Core\Models\Business;
use App\Core\Models\BusinessCategory;
use App\Core\Models\User;
use App\FlashDeal\Models\FlashDeal;
use App\Haku\Searchers\BusinessesByDistrict;
use App\Haku\Searchers\BusinessesByCategory;
use Illuminate\Support\Collection;
use Input;
use Request;
use Response;
use Settings;
use Util;
use View;

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
    protected function renderBusinesses($paginator, $businesses, $heading, $title = '', $meta = [])
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
        list($lat, $lng) = Util::getCoordinates();

        $viewData['deals']   = $deals;
        $viewData['lat']     = $lat;
        $viewData['lng']     = $lng;
        $viewData['heading'] = $heading;
        $viewData['meta']    = (array) $meta;
        $viewData['title']   = $title ?: trans('common.home');

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
     * Show businesses of a category
     *
     * @param int    $id
     * @param string $slug
     *
     * @return View
     */
    public function category($id, $slug)
    {
        // Get the correct model based on first URL segment
        $isMasterCategory = strpos(Request::path(), 'categories') !== false;
        $categoryKeyword = $isMasterCategory ? 'mc_'.$id : 'tm_'.$id;

        $model = $isMasterCategory
            ? '\App\Appointment\Models\MasterCategory'
            : '\App\Appointment\Models\TreatmentType';
        $instance = $model::findOrFail($id);

        $perPage = 15;
        $params = [
            'location' => Util::getCoordinates(),
            'from' => (Input::get('page', 1) - 1) * $perPage,
            'size' => $perPage
        ];
        $searchType = Input::get('type');
        if (!empty($searchType) && $searchType === 'district') {
            $params['keyword'] = Input::get('location');
            $params['category'] = $categoryKeyword;

            $s = new BusinessesByDistrict($params);
        } else {
            $params['keyword'] = $categoryKeyword;

            $s = new BusinessesByCategory($params);
        }

        $paginator = $s->search();

        // Extract list of businesses
        $items = $paginator->getCollection();

        $heading = $instance->name;

        $q = Input::get('q');

        if (strpos(Request::path(), 'treatments') !== false
            || strpos(Request::path(), 'categories') !== false) {
            $keyword = $instance->keywords()->where('keyword', '=', $q)->get();
            if ($keyword->count()) {
                $heading = $q;
            }
        }

        // Add meta data to this page
        $meta['description'] = $instance->description;

        // Change page title
        $title = $instance->name;

        return $this->renderBusinesses($paginator, $items, $heading, $title, $meta);
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

    public function about()
    {
        $categories = BusinessCategory::getAll();

        // Master categories
        $masterCategories = MasterCategory::getAll();

        return $this->render('about', [
            'categories'       => $categories,
            'masterCategories' => $masterCategories,
        ]);
    }

    public function business()
    {
        return $this->render('business-page');
    }

    public function intro()
    {
        return $this->render('intro');
    }

    /**
     * Show content as static pages
     *
     * @return View
     */
    public function staticPage()
    {
        $id = Request::segment(1);
        $page = array_get([
            'terms'  => 'terms_conditions',
            'policy' => 'privacy_cookies',
        ], $id, null);

        if ($page && ($content = Settings::getByLanguage($page, App::getLocale()))) {
            return $this->render('pages', [
                'title'   => trans('home.pages.'.$page),
                'content' => $content
            ]);
        }

        App::abort(404);
    }
}
