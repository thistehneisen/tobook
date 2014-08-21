<?php namespace App\Appointment\Controllers;

use View;
use App\Core\Controllers\Base;

class Services extends Base
{

    public function index()
    {
        return View::make('as.services.index');
    }
}
