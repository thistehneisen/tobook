<?php namespace App\Appointment\Controllers;

use View;
use App\Core\Controllers\Base;

class Index extends Base
{

    public function index()
    {
        return View::make('as.index.index');
    }
}
