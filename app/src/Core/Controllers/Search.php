<?php namespace App\Core\Controllers;

use View, Input, DB, Util, Response, App, Config, Es, Paginator, Log;
use App\Core\Models\Business;
use Carbon\Carbon, Session;

class Search extends Base
{
    protected $viewPath = 'front.search';

    public function index()
    {
        $businesses = Business::search(e(Input::get('q')));

        $lat = e(Input::get('lat'));
        $lng = e(Input::get('lng'));

        // If there is lat and lng values, we'll store in Session so that we
        // don't need to as again
        if ($lat && $lng) {
            Session::set('lat', $lat);
            Session::set('lng', $lng);
        } else {
            // Helsinki
            $lat = '60.1733244';
            $lng = '24.9410248';

            $location = Input::get('location');
            try {
                list($lat, $lng) = Util::geocoder($location);
            } catch (\Exception $ex) {
                // Silently failed
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
