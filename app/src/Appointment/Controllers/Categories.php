<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\ServiceCategory;

class Categories extends AsBase
{
    use App\Appointment\Traits\Crud;

    protected $viewPath = 'modules.as.services.category';
    protected $langPrefix = 'as.services.category';
    protected $modelClass = 'App\Appointment\Models\ServiceCategory';

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
