<?php namespace App\Appointment\Controllers;

use Input, Confide;

class ExtraServices extends AsBase
{
    // TODO: why do we have both CRUD and App\Appointment\Traits\Crud?!
    use \CRUD;

    protected $crudOptions = [
        'langPrefix' => 'as.services.extras',
        'modelClass' => 'App\Appointment\Models\ExtraService',
        'layout'     => 'modules.as.layout',
        'lomake' => [
            'length' => [
                'type' => 'Spinner',
                'values' => 10,
                'options' => ['class' => 'form-control input-sm spinner', 'data-positive' => 'true', 'data-inc' => 5]
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
