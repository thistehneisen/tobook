<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;

class Rooms extends AsBase
{
    use \CRUD;
    protected $viewPath = 'modules.as.services.rooms';
    protected $crudOptions = [
        'modelClass' => 'App\Appointment\Models\Room',
        'langPrefix' => 'as.services.rooms',
        'layout' => 'modules.as.layout',
        'showTab' => true,
        'bulkActions' => [],
        'indexFields' => ['name', 'description'],
    ];

    /**
     * {@inheritdoc}
     */
    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate($this->user);
        $item->saveOrFail();

        return $item;
    }
}
