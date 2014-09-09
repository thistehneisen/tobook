<?php namespace App\Core\Controllers;

use View, Input, DB;
use App\Core\Models\BusinessCategory;
use App\Core\Models\User as CustomerModel;

class Search extends Base
{
    public function index()
    {
        $query = Input::get('query', '');
        $location = Input::get('location', '');

        if ($query or $location) {
            $businesses = CustomerModel::whereHas('businessCategories', function ($q) use ($query, $location) {
                $q->where('name', 'LIKE', '%'.$query.'%')
                    ->orWhere('keywords', 'LIKE', '%'.$query.'%');
            })->where('city', 'LIKE', '%'.$location.'%')
            ->get();
        } else {
            $businesses = CustomerModel::get();
        }
        var_dump(DB::getQueryLog());
        return View::make('front.search.index', [
            'businesses' => $businesses
        ]);
    }
}
