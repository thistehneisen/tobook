<?php namespace App\Restaurant\Controllers;

use Input;
use App\Core\Controllers\Base;

class Group extends Base
{
    use \CRUD;

    protected $crudOptions = [
        'langPrefix'    => 'rb.groups',
        'modelClass'    => 'App\Restaurant\Models\Group',
        'layout'        => 'modules.rb.layout',
    ];

    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate($this->user);
        $item->saveOrFail();
    }
}
