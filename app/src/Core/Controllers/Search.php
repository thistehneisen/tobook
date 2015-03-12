<?php namespace App\Core\Controllers;

use App\Core\Models\Business;
use Carbon\Carbon;
use Input;
use Settings;
use Util;

class Search extends Front
{
    protected $viewPath = 'front';

    /**
     * Show search result
     *
     * @return [type] [description]
     */
    public function index()
    {
        $keyword    = Input::get('q');
        $date       = Input::get('date');
        $time       = Input::get('time');
        $location   = Input::get('location');
        $businesses = Business::search(e($keyword));

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

        return $this->render('businesses', [
            'businesses' => $businesses->getItems(),
            'pagination' => $businesses->links(),
            'deals'      => $deals,
            'lat'        => $lat,
            'lng'        => $lng,
            'heading'    => $heading,
        ]);
    }
}
