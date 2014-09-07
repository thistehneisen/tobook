<?php namespace App\Appointment\Controllers;

use App\Appointment\Models\Service;

class Reports extends AsBase
{
    protected $viewPath = 'modules.as.reports';

    public function employees()
    {
        $services = Service::all();
        // Prepare data
        return $this->render('employees', [
            'services' => array_combine($services->lists('id'), $services->lists('name'))
        ]);
    }
}
