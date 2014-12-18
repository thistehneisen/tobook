<?php namespace App\Core\Controllers;

use View, Input, DB, Util, Response, Geocoder, App, Config, Es, Paginator, Log;
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
        $location = Input::get('location');
        if (!empty($location)) {
            try {
                $geocode = Geocoder::geocode($location);
                $long = $geocode->getLatitude();
                $lat = $geocode->getLongitude();
            } catch (\Exception $ex) {
                Log::info('Cannot search location', $location);
            }
        }

        return $this->render('index', [
            'businesses' => $businesses,
            'lat'        => $long,
            'lng'        => $lat,
            'now'        => Carbon::now()
        ]);
    }
}
