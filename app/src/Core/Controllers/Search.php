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

        $geocode = $geocoder->geocode('Helsinki');

        return View::make('front.search.index', [
            'businesses' => $businesses,
            'geocode' => $geocode
        ]);
    }
}
