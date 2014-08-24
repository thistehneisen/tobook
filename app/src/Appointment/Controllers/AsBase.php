<?php namespace App\Appointment\Controllers;

use App, Confide;

class AsBase extends \App\Core\Controllers\Base
{
    public function __construct()
    {
        parent::__construct();
        $this->serviceModel = App::make('App\Appointment\Models\Service');
        $this->extraServiceModel = App::make('App\Appointment\Models\ExtraService');
        $this->serviceTimeModel = App::make('App\Appointment\Models\ServiceTime');
        $this->categoryModel = App::make('App\Appointment\Models\ServiceCategory');
        $this->resourceModel = App::make('App\Appointment\Models\Resource');
        $this->employeeModel = App::make('App\Appointment\Models\Employee');
    }
}
