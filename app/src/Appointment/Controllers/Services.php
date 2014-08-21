<?php namespace App\Appointment\Controllers;

use View, URL, Confide, Redirect;

class Services extends Base
{
    public function __construct()
    {
        // @todo: Check membership. It's better to have a filter and attach it
        // to this route
    }

    public function index(){
        return View::make('as.services.index');
    }
}
