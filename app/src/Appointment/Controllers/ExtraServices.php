<?php namespace App\Appointment\Controllers;

use Input, Confide;

class ExtraServices extends AsBase
{
    use \CRUD;

    protected $crudOptions = [
        'modelClass' => 'App\Appointment\Models\ExtraService',
        'langPrefix' => 'as.services.extras',
        'layout' => 'modules.as.layout',
        'bulkActions' => [],
        'lomake' => [
            'length' => [
                'type' => 'Spinner',
                'options' => [
                    'class' => 'form-control input-sm spinner',
                    'data-positive' => 'true',
                    'data-inc' => 5
                ]
            ],
        ]
    ];

    /**
     * @{@inheritdoc}
     */
    public function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate(Confide::user());
        $item->saveOrFail();

        return $item;
    }
}
