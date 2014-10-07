<?php namespace App\Restaurant\Controllers;

use Input;
use App\Core\Controllers\Base;

class Service extends Base
{
    use \CRUD;

    protected $crudOptions = [
        'langPrefix'    => 'rb.services',
        'modelClass'    => 'App\Restaurant\Models\Service',
        'layout'        => 'layouts.default',
    ];

    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate($this->user);
        $item->saveOrFail();
    }
}
