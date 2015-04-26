<?php namespace App\Core\Controllers;

use App\Core\Models\Business;
use Input;
use Session;
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
        $q        = Input::get('q');
        $date     = Input::get('date');
        $time     = Input::get('time');
        $location = Input::get('location');

        $keyword = (empty($q) && !empty($location))
            ? $location
            : $q;

        $isSearchByLocation = (empty($q) && !empty($location)) ? true : false;

        $paginator = empty($keyword)
            ? Business::getAll()
            : Business::search(e($keyword), ['isSearchByLocation' => $isSearchByLocation]);

        // Extract list of businesses
        $items = $paginator->getItems();
        // Make heading
        $heading = trans('home.search.results', [
            'keyword'  => $q,
            'date'     => !empty($date) ? $date : strtolower(trans('home.search.date')),
            'time'     => !empty($time) ? $time : strtolower(trans('home.search.time')),
            'location' => Util::getCurrentLocation(),
            'total'    => $paginator->getTotal(),
        ]);

        return $this->renderBusinesses($paginator, $items, $heading);
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
