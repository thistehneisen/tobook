<?php namespace App\Core\Controllers;

use View, Input, DB, Util, Response, Geocoder, App, Config, Es, Paginator;
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
        $page     = Input::get('page', 1);

        $businesses = new \Illuminate\Support\Collection;

        if(!empty($q) || !empty($location)){
            $data = [];
            $perPage = (int) Config::get('view.perPage');
            $params['index'] = 'businesses';
            $params['type']  = 'business';
            $params['from']  = ($page * $perPage) - $perPage;
            $params['size']  = $perPage;

            $query['bool']['should'][]['match']['name']          = $q;
            $query['bool']['should'][]['match']['business_name'] = $q;
            $query['bool']['should'][]['match']['category_name'] = $q;
            $query['bool']['should'][]['match']['description']   = $q;

            $filter = [
                'exists' => [ 'field' => 'business_name' ]
            ];//for using later

            if(!empty($location)) {
                $query['bool']['should'][]['match']['city'] = $location;
                $query['bool']['should'][]['match']['country'] = $location;
            }
            $params['body']['query']['filtered'] = [
                "filter" => $filter,
                "query"  => $query
            ];
            $result = Es::search($params);
            $total = $result['hits']['total'];
            foreach ($result['hits']['hits'] as $row) {
                $data[] = BusinessModel::find($row['_id']);
            }
            $businesses =  Paginator::make($data, $total, $perPage);
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
