<?php namespace App\Core\Controllers;

use App\Core\Models\Business;
use Input;
use Request;
use Response;
use Session;
use Settings;
use Util;

class Search extends Front
{
    protected $viewPath = 'front';

    /**
     * Show search result
     *
     * @return View
     */
    public function index()
    {
        $keyword     = Input::get('q');
        $date        = Input::get('date');
        $time        = Input::get('time');
        $location    = Input::get('location');
        $businesses = empty($keyword)
            ? Business::getAll()
            : Business::search(e($keyword));

        $nextPageUrl = $this->getNextPageUrl($businesses);
        $view        = [
            'businesses' => $businesses->getItems(),
            'nextPageUrl' => $nextPageUrl,
        ];

        // If this is a Show more request, return the view only
        if (Request::ajax()) {
            return Response::json([
                'businesses' => $businesses->getItems(),
                'html'       => $this->render('el.sidebar', $view)->render()
            ]);
        }

        // Get the deals from result businesses
        $deals = $this->getDealsOfBusinesses($businesses);

        // Extract coordinates from query string input
        list($lat, $lng) = Util::getCoordinates();

        // Make heading
        $heading = trans('home.search.results', [
            'keyword'  => $keyword,
            'date'     => !empty($date) ? $date : strtolower(trans('home.search.date')),
            'time'     => !empty($time) ? $time : strtolower(trans('home.search.time')),
            'location' => !empty($location) ? $location : Settings::get('default_location'),
            'total'    => $businesses->getTotal(),
        ]);

        $view['deals']       = $deals;
        $view['lat']         = $lat;
        $view['lng']         = $lng;
        $view['heading']     = $heading;
        $view['source']      = 'search'; // Indicator to toggle correc;

        return $this->render('businesses', $view);
    }

    /**
     * Receive lat and lng value, update to Session so users won't be ask again
     *
     * @return void
     */
    public function updateLocation()
    {
        $lat = Input::get('lat');
        $lng = Input::get('lng');
        if (!empty($lat) && !empty($lng)) {
            Session::set('lat', $lat);
            Session::set('lng', $lng);
        }
    }
}
