<?php namespace App\Core\Controllers;

use App\Core\Models\Business;
use Carbon\Carbon;
use Input;
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
        $keyword = Input::get('q');
        $businesses = Business::search(e($keyword));

        // Get the deals from result businesses
        $deals = $this->getDealsOfBusinesses($businesses);

        // Extract coordinates from query string input
        list($lat, $lng) = Util::getCoordinates();

        return $this->render('businesses', [
            'businesses' => $businesses,
            'pagination' => $businesses->links(),
            'deals'      => $deals,
            'lat'        => $lat,
            'lng'        => $lng,
            'heading'    => trans('home.search.results', ['keyword' => $keyword])
        ]);
    }
}
