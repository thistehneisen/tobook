<?php namespace App\Appointment\Controllers;

use App, Confide;

class AsBase extends \App\Core\Controllers\Base
{
    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = App::make('App\Appointment\Models\ServiceCategory');
    }
}
