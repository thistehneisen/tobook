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
            $params['index'] = 'businesses';
            $params['type']  = 'business';
            $params['size']  = Config::get('view.perPage');

            $query['bool']['should'][]['match']['name']          = $q;
            $query['bool']['should'][]['match']['business_name'] = $q;
            $query['bool']['should'][]['match']['category_name'] = $q;
            $query['bool']['should'][]['match']['description']   = $q;

            $filter = [];//for using later

            if(!empty($location)) {
                $query['bool']['should'][]['match']['city'] = $location;
                $query['bool']['should'][]['match']['country'] = $location;
            }
            $params['body']['query']['filtered'] = [
                "filter" => $filter,
                "query"  => $query
            ];
            $result = Es::search($params);
            foreach ($result['hits']['hits'] as $row) {
                //Temporary cheating for empty business name
                if(!empty($row['_source']['business_name'])){
                    $data[] = BusinessModel::find($row['_id']);
                }
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
