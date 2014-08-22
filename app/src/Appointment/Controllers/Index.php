<?php namespace App\Appointment\Controllers;

use View;
use App\Core\Controllers\Base;

class Index extends Base
{
    /**
     * Show booking calendar
     *
     * @return View
     */
    public function index()
    {
        return View::make('modules.as.index.index');
    }
}
