<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;

class Resources extends AsBase
{
    use \CRUD;
    protected $viewPath = 'modules.as.services.resource';
    protected $crudOptions = [
        'modelClass' => 'App\Appointment\Models\Resource',
        'langPrefix' => 'as.services.resources',
        'layout' => 'modules.as.layout',
        'showTab' => true,
        'bulkActions' => [],
        'indexFields' => ['name', 'description'],
        'lomake' => [
            'quantity' => [
                'type' => 'Spinner',
                'options' => [
                    'class' => 'form-control input-sm spinner',
                    'data-positive' => 'true',
                    'data-inc' => 1
                ]
            ],
        ]
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
