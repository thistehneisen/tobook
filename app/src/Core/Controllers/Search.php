<?php namespace App\Core\Controllers;

use View, Input, DB, Util, Response, App, Config, Es, Paginator, Log;
use App\Core\Models\Business;
use Carbon\Carbon;

class Search extends Base
{
    protected $viewPath = 'front.search';

    public function index()
    {
        $businesses = Business::search(e(Input::get('q')));
        list($lat, $lng) = Util::getCoordinates();

        return $this->render('index', [
            'businesses'     => $businesses,
            'lat'            => $lat,
            'lng'            => $lng,
            'now'            => Carbon::now(),
            'businessesJson' => json_encode($businesses->getItems()),
        ]);
    }
}
