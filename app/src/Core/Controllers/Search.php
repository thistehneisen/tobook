<?php namespace App\Core\Controllers;

use App\Core\Models\Business;
use Input;
use Session;
use Util;
use App\Haku\Searchers\BusinessesByName;

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

        $coordinates = [0, 0];

        try {
            $coordinates = Util::getCoordinates();
        } catch(\Exception $ex){
            Log::warning('Cannot geocode location: '.$ex->getMessage());
        }

        $searchParams = [
            'keyword' => $keyword,
            'location' => $coordinates,
        ];

        $searcher = new BusinessesByName($searchParams);
        $paginator = empty($keyword)
            ? Business::getAll()
            : $searcher->search();

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
