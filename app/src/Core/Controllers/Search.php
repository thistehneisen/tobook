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
            $queryString = '%'.$q.'%';
            $query = $query->whereHas(
                'businessCategories',
                function ($query) use ($queryString) {
                    return $query->where('name', 'LIKE', $queryString)
                        ->orWhere('keywords', 'LIKE', $queryString);
                }
            )->orWhere('business_name', 'LIKE', $queryString);
        }

        if (!empty($location)) {
            $query = $query->where('city', 'LIKE', '%'.$location.'%');
        }

        $businesses = $query
            ->where('business_name', '!=', '')
            ->paginate(Config::get('view.perPage'));

        $geocode = Geocoder::geocode($location ?: 'Helsinki');

        return $this->render('index', [
            'businesses' => $businesses,
            'lat'        => $geocode->getLatitude(),
            'lng'        => $geocode->getLongitude(),
        ]);
    }
}
