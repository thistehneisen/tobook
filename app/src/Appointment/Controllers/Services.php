<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config;
use App\Appointment\Models\ServiceCategory;

class Services extends AsBase
{

    public function index()
    {
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $services = $this->serviceModel
            ->ofCurrentUser()
            ->paginate($perPage);

        return View::make('modules.as.services.index', [
            'services' => $services
        ]);
    }

    public function create()
    {
        //TODO add service and service time
        return View::make('modules.as.services.create');
    }
}
