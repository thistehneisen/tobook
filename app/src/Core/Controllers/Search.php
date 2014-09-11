<?php namespace App\Core\Controllers;

use View, Input, DB, Util, Response, Geocoder;
use App\Core\Models\BusinessCategory;
use App\Core\Models\User as BusinessModel;

class Search extends Base
{
    public function index()
    {
        $query = Input::get('query', '');
        $location = Input::get('location', '');
        $businesses = BusinessModel::whereHas('businessCategories', function ($q) use ($query, $location) {
            $q->where('name', 'LIKE', '%'.$query.'%')
                ->orWhere('keywords', 'LIKE', '%'.$query.'%');
        })->where('city', 'LIKE', '%'.$location.'%')
        ->paginate(100);

        $geocoder = new Geocoder\Geocoder();
        $geocoder->registerProviders([
            new Geocoder\Provider\GoogleMapsProvider(
                new Geocoder\HttpAdapter\CurlHttpAdapter(), 'en', 'Finland', false
            ),
        ]);

        $geocode = $geocoder->geocode('Salounaukio 3, Lahti, Finland');

        return View::make('front.search.index', [
            'businesses' => $businesses,
            'geocode' => $geocode
        ]);
    }

    public function ajaxGetServices()
    {
        $categories = BusinessCategory::getAll();
        $result = [];
        foreach ($categories as $cat) {
            $result[] = $cat->name;
            if ($cat->keywords !== '') {
                $result = array_merge($result, array_map('trim', explode(',', $cat->keywords)));
            }
            foreach ($cat->children as $subCat) {
                $result[] = $subCat->name;
                if ($subCat->keywords !== '') {
                    $result = array_merge($result, array_map('trim', explode(',', $subCat->keywords)));
                }
            }
        }
        return Response::json($result, 200);
    }

    public function ajaxGetLocations()
    {
        $locations = DB::table('users')->select('city AS name')->where('city', '!=', '')->get();
        return Response::json($locations, 200);
    }

    public function ajaxShowBusiness($businessId)
    {
        $business = BusinessModel::find($businessId);
        return View::make('front.search._business', [
            'business' => $business
        ]);
    }
}
