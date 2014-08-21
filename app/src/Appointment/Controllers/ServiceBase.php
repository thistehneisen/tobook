<?php namespace App\Appointment\Controllers;
use App, Config, Request, Redirect, Input, Confide;
class ServiceBase extends \App\Core\Controllers\Base
{
    public function __construct()
    {
        $this->user_id = Confide::user()->id;
        $this->categoryModel = App::make('App\Appointment\Models\ServiceCategory');
    }
}
