<?php namespace App\Core\Controllers\Ajax;

use DB, Carbon\Carbon, Request, View;
use Illuminate\Support\Collection;
use App\Core\Models\BusinessCategory;
use App\Core\Models\User;

class Search extends Base
{
    public function __construct()
    {
        $this->beforeFilter('ajax', ['except' => 'showBusiness']);
    }

    /**
     * Prepare services data for typeahead
     *
     * @return JSON
     */
    public function getServices()
    {
        $categories = BusinessCategory::getAll();
        $result = [];
        foreach ($categories as $cat) {
            $result[] = $cat->name;
            $result = array_merge($result, $cat->keywords);
            foreach ($cat->children as $subCat) {
                $result[] = $subCat->name;
                $result = array_merge($result, $subCat->keywords);
            }
        }

        return $this->json($result);
    }

    /**
     * Prepare location data for typeadhead
     *
     * @return JSON
     */
    public function getLocations()
    {
        $locations = DB::table(with(new User)->getTable())
            ->select('city AS name')
            ->where('city', '!=', '')
            ->distinct()
            ->get();

        return $this->json($locations);
    }

    /**
     * Show information of a business
     *
     * @param int $id
     *
     * @return View
     */
    public function showBusiness($id)
    {
        $business = User::findOrFail($id);
        $coupons = $business->coupons()->active()->with('service')->get();
        $flashDeals = $business->flashDeals()
            ->with(['dates', 'service'])
            ->whereHas('dates', function($query) {
            return $query->where('remains', '>', 0)
                ->where('expire', '>=', Carbon::now());
            })
            ->get();

        // Filter out passed deals
        $deals = new Collection;
        foreach ($flashDeals as $deal) {
            $deal->active = $deal->dates->filter(function($item) {
                return $item->expire->gte(Carbon::now());
            });

            $deals->push($deal);
        }

        $view = $this->view('front.search.business', [
            'business'   => $business,
            'coupons'    => $coupons,
            'flashDeals' => $deals
        ]);

        if (Request::ajax()) {
            return $view;
        }

        return View::make('front.search.index', [
            'businesses' => new \Illuminate\Support\Collection([$business]),
            'single'     => $view,
            'lat'        => $business->lat,
            'lng'        => $business->lng,
        ]);
    }
}
