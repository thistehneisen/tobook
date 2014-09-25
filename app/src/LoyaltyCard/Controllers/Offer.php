<?php namespace App\LoyaltyCard\Controllers;

use Input;
use App\Core\Controllers\Base;

class Offer extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.lc.offers';
    protected $crudOptions = [
        'modelClass'    => 'App\LoyaltyCard\Models\Offer',
        'langPrefix'    => 'loyalty-card',
        'layout'        => 'layouts.default',
        'indexFields'   => ['name', 'required', 'total_used', 'is_auto_add', 'is_active'],
    ];

    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->total_used = 0;
        $item->user()->associate($this->user);
        $item->saveOrFail();

        return $item;
    }
}
