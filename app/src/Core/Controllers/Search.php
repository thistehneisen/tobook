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

        $lat = e(Input::get('lat'));
        $lng = e(Input::get('lng'));

        if (!($lat && $lng)) {
            // Helsinki
            $lat = '60.1733244';
            $lng = '24.9410248';

            $location = Input::get('location');
            if (!empty($location)) {
                try {
                    $geocode = Geocoder::geocode($location);
                    $lat = $geocode->getLatitude();
                    $lng = $geocode->getLongitude();
                } catch (\Exception $ex) {
                    Log::info('Cannot search location', $location);
                }
            }
        }

        return $this->render('index', [
            'businesses' => $businesses,
            'lat'        => $lat,
            'lng'        => $lng,
            'now'        => Carbon::now()
        ]);
    }
}
