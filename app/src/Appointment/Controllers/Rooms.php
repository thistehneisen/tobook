<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\Room;

class Rooms extends AsBase
{
    use App\Appointment\Traits\Crud;
    protected $viewPath = 'modules.as.services.rooms';
    protected $langPrefix = 'as.services.rooms';

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
