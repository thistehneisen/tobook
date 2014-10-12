<?php namespace App\Core\Controllers;

use View, Input, DB, Util, Response, Geocoder, App, Config, Es;
use App\Core\Models\BusinessCategory;
use App\Core\Models\User as BusinessModel;
use Carbon\Carbon;

class Search extends Base
{
    protected $viewPath = 'front.search';

    public function index()
    {
        $q        = e(Input::get('q'));
        $location = e(Input::get('location'));

        $businesses = new \Illuminate\Support\Collection;

        if(!empty($q) || !empty($location)){
            $data = [];
            $keyword = $q . ' ' . $location;
            $params['index'] = 'businesses';
            $params['type']  = 'business';
            $params['size']  = Config::get('view.perPage');
            $params['body']['query']['bool']['should'][]['match']['business_name'] = $q;
            $params['body']['query']['bool']['should'][]['match']['category_name'] = $q;
            $params['body']['query']['bool']['should'][]['match']['name'] =  $q;
            if(!empty($location)) {
                $params['body']['query']['bool']['should'][]['match']['city'] = $location;
                $params['body']['query']['bool']['should'][]['match']['city'] = $location;
            }
            $result = Es::search($params);
            foreach ($result['hits']['hits'] as $row) {
                $data[] = BusinessModel::find($row['_id']);
            }
            $businesses = new \Illuminate\Support\Collection($data);
        }

        $geocode = Geocoder::geocode($location ?: 'Helsinki');

        return $this->render('index', [
            'businesses' => $businesses,
            'lat'        => $geocode->getLatitude(),
            'lng'        => $geocode->getLongitude(),
            'now'        => Carbon::now()
        ]);
    }
}
