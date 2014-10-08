<?php namespace App\Restaurant\Controllers;

use Input;
use App\Core\Controllers\Base;

class Service extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.rb.services';
    protected $crudOptions = [
        'langPrefix'    => 'rb.services',
        'modelClass'    => 'App\Restaurant\Models\Service',
        'layout'        => 'modules.rb.layout',
    ];

    protected function upsertHandler($item)
    {
        $item->fill([
            'name'      => Input::get('name'),
            'start_at'  => date('H:i:s', strtotime(Input::get('start_at'))),
            'end_at'    => date('H:i:s', strtotime(Input::get('end_at'))),
            'price'     => Input::get('price'),
            'length'    => Input::get('length'),
        ]);
        $item->user()->associate($this->user);
        $item->saveOrFail();
    }
}
