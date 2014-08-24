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
        $categories = $this->categoryModel->ofCurrentUser()->lists('name','id');
        $resources  = $this->resourceModel->ofCurrentUser()->lists('name', 'id');
        $extras     = $this->extraServiceModel->ofCurrentUser()->lists('name', 'id');
        $employees  = $this->employeeModel->ofCurrentUser()->lists('name', 'id');
        //TODO add service and service time
        return View::make('modules.as.services.service.form', [
                'categories' => $categories,
                'resources'  => $resources,
                'extras'     => $extras,
                'employees'  => $employees
            ]);
    }
}
