<?php namespace App\Core\Controllers;

use App;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Service;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\TreatmentType;
use App\Core\Models\Business;
use App\Core\Models\BusinessCategory;
use App\Core\Models\User;
use App\Core\Models\Review;
use App\Haku\Searchers\BusinessesByCategory;
use App\Haku\Searchers\BusinessesByCategoryAdvanced;
use App\Haku\Searchers\BusinessesByDistrict;
use App\Haku\Searchers\BusinessesByCity;
use Illuminate\Support\Collection;
use Cookie;
use Input;
use Request;
use Response;
use Redirect;
use Session;
use Settings;
use Util;
use View;
use Validator;

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

        // Master categories
        $masterCategories = MasterCategory::getAll();

        $bookingCount = Booking::where('created_at', '>=', '2015-07-01')->count();

        // Should we display the modal in homepage?
        // @see: https://github.com/varaa/varaa/issues/644
        $iframeUrl = null;
        $cookieName = Settings::get('homepage_modal_cookie_name');
        $duration   = (int) Settings::get('homepage_modal_cookie_expiry_duration');
        Cookie::queue($cookieName, true, $duration);

        if ((bool) Settings::get('enable_homepage_modal', false)
            && Cookie::get($cookieName) !== true) {
            $iframeUrl  = Settings::get('homepage_modal_url');
            $iframeUrl .= '?lang=' . App::getLocale();
        }

        // Get 4 random busiensses which have discount #718
        $randomBusinesses = Business::getRamdomBusinesesHasDiscount(4);

        return $this->render('home', [
            'bookingCount'     => $bookingCount,
            'categories'       => $categories,
            'iframeUrl'        => $iframeUrl,
            'masterCategories' => $masterCategories,
            'randomBusinesses' => $randomBusinesses,
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
        $support = ['en', 'fi', 'lv', 'ru'];
        $lang = App::getLocale();

        if (!in_array($lang, $support)) {
            $lang = (is_tobook()) ? 'lv' : 'fi';
        }
        
        return $this->render('business.'. $lang);
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

     /**
     * Show review page to consumer
     *
     * @return View
     */
    public function review($id)
    {
        try {
            $user = User::findOrFail($id);
        } catch(\Exception $ex){
            App::abort(404);
        }

        return $this->render('neo-review', [
            'id' => $id,
            'name' => $user->business->slug,
            'showForm' => true
        ]);
    }

    /**
     * Handle review submission
     * 
     * @return Redirect
     */
    public function doReview($id)
    {
        try {
            $user = User::findOrFail($id);

            $rules = array(
               'environment'          => 'required',
               'service'              => 'required',
               'price_ratio'          => 'required',
               'g-recaptcha-response' => 'required|recaptcha'
            );

            $validator = Validator::make(Input::all(), $rules);
            
            $validator->setAttributeNames([
                'g-recaptcha-response' => 'reCaptcha'
            ]); 

            if ($validator->fails()) {
                $messages = $validator->messages();
                return Redirect::back()->withInput()->withErrors($messages);
            }

            $review = new Review();
            $review->fill(Input::all());
            $review->user()->associate($user);
            $review->setAvgRating();
            $review->saveOrFail();
        } catch(\Watson\Validating\ValidationException $ex){
            return Redirect::back()->withInput()->withErrors($ex->getErrors());
        }

        return Redirect::route('businesses.review', ['id' => $user->id, 'slug' => $user->business->slug])
            ->with('showSuccess', true);
    }

    /**
     * Test new layout
     * 
     * @return View
     */
    public function businessList($id, $slug)
    {   
        // Get the correct model based on first URL segment
        $isMasterCategory = strpos(Request::path(), 'categories') !== false;
        $type = $isMasterCategory ? 'category' : 'treatment';

        $model = ($type === 'category')
            ? '\App\Appointment\Models\MasterCategory'
            : '\App\Appointment\Models\TreatmentType';
        $instance = $model::findOrFail($id);

        $mcId    = null;
        $mcId    = ($type === 'category') ? $id : $instance->master_category_id;

        $keyword = null;
        $keyword = $instance->name;

        // Master categories
        $masterCategories = MasterCategory::getAll();

                // Add meta data to this page
        $meta['description'] = $instance->description;
        $categoriesTypes = [];

        foreach ($masterCategories as $_category) {
            $category = [];
            $category['id']   = $_category->id;
            $category['name'] = $_category->name;
            $category['url']  = $_category->url;
            $category['treatments'] = [];
            foreach ($_category->treatments as $_treatment) {
                $treatment = [];
                $treatment['name'] = $_treatment->name; 
                $treatment['url']  = $_treatment->url;
                $category['treatments'][] = $treatment;
            }
            $categoriesTypes[] = $category;
        }

        // Get 1 random busienss which have discount #718
        $collection = Business::getRamdomBusinesesHasDiscount(1);
        $_randomBusiness = $collection->first();
        $randomBusiness = [];

        if (!empty($_randomBusiness->id)) { 
            $randomBusiness['id']                     = $_randomBusiness->id;
            $randomBusiness['name']                   = $_randomBusiness->name;
            $randomBusiness['image']                  = $_randomBusiness->image;
            $randomBusiness['businessUrl']            = $_randomBusiness->businessUrl;
            $randomBusiness['serviceName']            = $_randomBusiness->randomMostDiscountedService->name;
            $randomBusiness['discountPrice']          = $_randomBusiness->randomMostDiscountedService->price;
            $randomBusiness['discountPercent']        = $_randomBusiness->discountPercent;
            $randomBusiness['businessUrlWithService'] = route('business.index', [
                'id' => $_randomBusiness->user_id, 
                'slug' => $_randomBusiness->slug, 
                'serviceId' => $_randomBusiness->randomMostDiscountedService->id
            ]);
        }

        // Change page title
        $title = $instance->name;
        
        $services = Business::getSearchableServices();

        return $this->render('business.list',[
            'id'              => $id,
            'mcId'            => $mcId,
            'keyword'         => $keyword,
            'type'            => $type,
            'mcs'             => $masterCategories,
            'mctcs'           => json_encode($categoriesTypes),
            'business'        => json_encode($randomBusiness),
            'services'        => json_encode($services),
            'title'           => $title,
            'meta'            => $meta
        ]);
    }

    /**
     * Handle request from new business list layout 
     * 
     * @return json
     */
    public function search()
    {
        $id   = Input::get('id');
        $type = Input::get('type');
        
        $categoryKeyword = $type . '_' . $id;

        $model = ($type === 'category')
            ? '\App\Appointment\Models\MasterCategory'
            : '\App\Appointment\Models\TreatmentType';
        $instance = $model::findOrFail($id);

        $perPage = 15;
        $params = [
            // 'location' => Util::getCoordinates(),
            'from' => (Input::get('page', 1) - 1) * $perPage,
            'size' => $perPage
        ];
        $minPrice = (int)Input::get('min_price');
        $maxPrice = (int)Input::get('max_price');

        $params['has_discount'] = (Input::get('show_discount') == 'true') ? true : false;
        $params['min_price']    = $minPrice;
        $params['max_price']    = $maxPrice;
        $params['keyword']      = $categoryKeyword;
        $params['city']         = Input::get('city');

        $s = new BusinessesByCategory($params);
        

        $paginator = $s->search();

        $items = $paginator->getItems();

        $businesses = [];
        $services = [];

        foreach ($items as $item) {
            if ($item->isHidden) {
                continue;
            }

            $services = [];
            $priceRanges = [];

            if (!$item->isBookingDisabled) {
                $query = Service::where('user_id', $item->user_id)
                    ->where('is_active', '=', true);

                if ($type === 'category') {
                    $query = $query->where('master_category_id', $id);
                } else {
                    $query = $query->where('treatment_type_id', $id);
                }

                $query = $query->where(function($query) use($minPrice, $maxPrice) {
                    return $query->where('price', '>=', $minPrice)
                                ->where('price', '<=', $maxPrice);
                });
                
                $services = $query->orderBy('price', 'desc')->limit(5)->get();


                foreach ($services as $service) {
                    $priceRanges[$service->id] = $service->priceRange;
                }
            }

            if (count($services)) {
                $item['services']        = $services;
                $item['price_range']     = $priceRanges;
                $item['image_url']       = (!empty($item->images->first())) ? $item->images->first()->getPublicUrl() : '';
                $item['user_email']      = $item->user->email;
                $item['payment_options'] = $item->paymentOptions;
                $item['businessUrl']     = $item->businessUrl;
                $item['hasDiscount']     = $item->hasDiscount;
                $item['avg_total']       = $item->reviewScore;
                $item['review_count']    = $item->reviewCount;
                $businesses[] = $item;
            }
        }

        return Response::json([
            'businesses' => $businesses,
            'current'    => $paginator->getCurrentPage(),
            'count'      => $paginator->getLastPage(),
        ]);
    }

}
