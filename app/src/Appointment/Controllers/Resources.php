<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\Resource;

class Resources extends AsBase
{
    use App\Appointment\Traits\Crud;
    protected $viewPath = 'modules.as.services.resource';
    protected $langPrefix = 'as.services.resources';

    /**
     * {@inheritdoc}
     */
    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate($this->user);
        return $item;
    }
}
