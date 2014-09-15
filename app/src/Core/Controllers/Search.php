<?php namespace App\Core\Controllers;

use View, Input, DB, Util, Response, Geocoder, App, Config;
use App\Core\Models\BusinessCategory;
use App\Core\Models\User as BusinessModel;

class Search extends Base
{
    protected $viewPath = 'front.search';

    public function index()
    {
        $q        = e(Input::get('q'));
        $location = e(Input::get('location'));

        $query = with(new BusinessModel)->newQuery();
        if (!empty($q)) {
            $query = $query->whereHas(
                'businessCategories',
                function ($query) use ($q) {
                    return $query->where('name', 'LIKE', '%'.$q.'%')
                        ->orWhere('keywords', 'LIKE', '%'.$q.'%');
                }
            );
        }

        if (!empty($location)) {
            $query = $query->where('city', 'LIKE', '%'.$location.'%');
        }

        $businesses = $query->paginate(Config::get('view.perPage'));

        $geocoder = new Geocoder\Geocoder();
        $geocoder->registerProviders([
            new Geocoder\Provider\GoogleMapsProvider(
                new Geocoder\HttpAdapter\CurlHttpAdapter(), App::getLocale(), $location, false
            ),
        ]);

        $geocode = $geocoder->geocode('Helsinki');

        return $this->render('index', [
            'businesses' => $businesses,
            'geocode' => $geocode
        ]);
    }
}
