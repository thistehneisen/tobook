<?php namespace App\Appointment\Controllers;

use App\Appointment\Models\Service;
use App\Appointment\Reports\Employee as EmployeeReport;

class Reports extends AsBase
{
    protected $viewPath = 'modules.as.reports';

    public function employees()
    {
        $services = Service::ofCurrentUser()->get();

        return $this->render('employees', [
            'services' => array_combine($services->lists('id'), $services->lists('name')),
            'report'     => new EmployeeReport()
        ]);
    }
}
