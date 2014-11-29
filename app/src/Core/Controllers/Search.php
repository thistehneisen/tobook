<?php namespace App\Core\Controllers;

use View, Input, DB, Util, Response, Geocoder, App, Config, Es, Paginator;
use App\Core\Models\Business;
use Carbon\Carbon;

class Search extends Base
{
    protected $viewPath = 'front.search';

    public function index()
    {
        $businesses = Business::search(e(Input::get('q')));

        // Helsinki
        $long = '60.1733244';
        $lat = '24.9410248';
        $location = e(Input::get('location'));
        if (!empty($location)) {
            $geocode = Geocoder::geocode($location);
            $long = $geocode->getLatitude();
            $lat = $geocode->getLongitude();
        }

        return $this->render('index', [
            'businesses' => $businesses,
            'lat'        => $long,
            'lng'        => $lat,
            'now'        => Carbon::now()
        ]);
    }
}
