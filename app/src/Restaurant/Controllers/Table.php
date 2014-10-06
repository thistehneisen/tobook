<?php namespace App\Restaurant\Controllers;

use Input;
use App\Core\Controllers\Base;

class Table extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.rb.tables';
    protected $crudOptions = [
        'langPrefix' => 'rb.tables',
        'modelClass' => 'App\Restaurant\Models\Table',
        'layout'     => 'layouts.default',
    ];

    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate($this->user);
        $item->saveOrFail();
    }
}
