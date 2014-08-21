<?php namespace App\Appointment\Controllers;

use View;
use App\Core\Controllers\Base;

class Services extends Base
{

    public function index(){
        return View::make('as.services.index');
    }

    public function create()
    {
        return View::make('as.services.create');
    }

    public function categories()
    {
        return View::make('as.services.categories');
    }

    public function resources()
    {
        return View::make('as.services.resources');
    }
}
