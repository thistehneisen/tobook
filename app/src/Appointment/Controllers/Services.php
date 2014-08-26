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

    public function index()
    {
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $services = Service::ofCurrentUser()
            ->with('category')->paginate($perPage);

        return View::make('modules.as.services.index', [
            'services' => $services
        ]);
    }

    public function create()
    {
        $categories = ServiceCategory::ofCurrentUser()->lists('name','id');
        $resources  = Resource::ofCurrentUser()->lists('name', 'id');
        $extras     = ExtraService::ofCurrentUser()->lists('name', 'id');
        $employees  = Employee::ofCurrentUser()->get();
        //TODO add service and service time
        return View::make('modules.as.services.service.form', [
                'categories' => $categories,
                'resources'  => $resources,
                'extras'     => $extras,
                'employees'  => $employees
            ]);
    }

    public function doCreate(){
        $errors = $this->errorMessageBag(trans('common.err.unexpected'));

        try {
            $service = new Service;
            $service->fill(Input::all());
            // Attach user
            $service->user()->associate($this->user);
            $category_id = (int) Input::get('category_id');
            if(!empty($category_id)){
                $category = Category::find($category_id);
                $service->category()->associate($category);
            }

            $service->saveOrFail();

            $employees = Input::get('employees', array());
            $plustimes = Input::get('plustimes');

            foreach ($employees as $employee_id) {
                $employee = Employee::find($employee_id);
                $emplyeeService = new EmployeeService;
                $emplyeeService->service()->associate($service);
                $emplyeeService->employee()->associate($employee);
                $emplyeeService->plustime = $plustimes[$employee_id];
                $emplyeeService->saveOrFail();
            }

            return Redirect::route('as.services.index')
                ->with('messages', $this->successMessageBag(
                    trans('as.services.success_add_service')
                ));
        } catch (\Watson\Validating\ValidationException $ex) {
            $errors = $ex->getErrors();
        }

        return Redirect::back()->withInput()->withErrors($errors, 'top');
    }
}
