<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Resource;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Employee;

class Services extends AsBase
{
    use App\Appointment\Traits\Crud;
    protected $viewPath = 'modules.as.services.service';
    protected $langPrefix = 'as.services';

    /**
     * {@inheritdoc}
     */
    public function upsert($id = null)
    {
         $service = ($id !== null)
            ? Service::findOrFail($id)
            : new Service();
        $categories = ServiceCategory::ofCurrentUser()->lists('name','id');
        $resources  = Resource::ofCurrentUser()->lists('name', 'id');
        $extras     = ExtraService::ofCurrentUser()->lists('name', 'id');
        $employees  = Employee::ofCurrentUser()->get();

        return $this->render('form', [
            'service'    => $service,
            'categories' => $categories,
            'resources'  => $resources,
            'extras'     => $extras,
            'employees'  => $employees
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertHandler($service)
    {
        $service->fill(Input::all());
        // Attach user
        $service->user()->associate($this->user);
        $categoryId = (int) Input::get('category_id');
        if (!empty($categoryId)) {
            $category = ServiceCategory::find($categoryId);
            $service->category()->associate($category);
        }

        $service->saveOrFail();

        $employees = Input::get('employees', []);
        $plustimes = Input::get('plustimes');
        $service->employees()->detach($employees);
        foreach ($employees as $employeeId) {
            $employee = Employee::find($employeeId);
            $employeeService = new EmployeeService();
            $employeeService->service()->associate($service);
            $employeeService->employee()->associate($employee);
            $employeeService->plustime = $plustimes[$employeeId];
            $employeeService->saveOrFail();
        }

        return $service;
    }
}
