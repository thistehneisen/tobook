<?php namespace App\Restaurant\Controllers;

use Input;
use App\Core\Controllers\Base;

class Menu extends Base
{
    use \CRUD;

    protected $crudOptions = [
        'langPrefix'    => 'rb.menus',
        'modelClass'    => 'App\Restaurant\Models\Menu',
        'layout'        => 'layouts.default'
    ];

    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate($this->user);
        $item->saveOrFail();
    }
}
