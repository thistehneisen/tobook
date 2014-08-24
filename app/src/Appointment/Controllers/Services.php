<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\EmployeeService;

class Services extends AsBase
{

    public function index()
    {
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $services = $this->serviceModel
            ->ofCurrentUser()
            ->with('category')->paginate($perPage);

        return View::make('modules.as.services.index', [
            'services' => $services
        ]);
    }

    public function create()
    {
        $categories = $this->categoryModel->ofCurrentUser()->lists('name','id');
        $resources  = $this->resourceModel->ofCurrentUser()->lists('name', 'id');
        $extras     = $this->extraServiceModel->ofCurrentUser()->lists('name', 'id');
        $employees  = $this->employeeModel->ofCurrentUser()->get();
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
                $category = $this->serviceModel->find($category_id);
                if(!empty($category)){
                    $service->category()->associate($category);
                }
            }

            $service->saveOrFail();

            $employees = Input::get('employees', array());
            $plustimes = Input::get('plustimes');

            foreach ($employees as $employee_id) {
                $employee = $this->employeeModel->find($employee_id);
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
