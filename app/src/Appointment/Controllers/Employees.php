<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeDefaultTime;
use App\Appointment\Models\Service;
use Carbon\Carbon;
class Employees extends AsBase
{
    use App\Appointment\Traits\Crud;

    protected $viewPath = 'modules.as.employees';
    protected $langPrefix = 'as.employees';

    /**
     * {@inheritdoc}
     */
    public function upsert($id = null)
    {
        $services = Service::ofCurrentUser()->lists('name', 'id');
        $employee = ($id !== null)
            ? Employee::findOrFail($id)
            : new Employee;

        return $this->render('form', [
            'services' => $services,
            'employee' => $employee
        ]);
    }

    public function doCreate(){
        if (Input::hasFile('avatar')) {
            $file            = Input::file('avatar');
            $destinationPath = '/uploads/avatars/';
            $filename        = str_random(6) . '_' . $file->getClientOriginalName();
            $uploadSuccess   = $file->move($destinationPath, $filename);
        }

        try {
            $employee = new Employee;
            $employee->fill(Input::all());
            // Attach user
            $employee->user()->associate($this->user);
            $employee->saveOrFail();

            return Redirect::route('as.employees.index')
                ->with('messages', $this->successMessageBag(
                    trans('as.employees.success_add_employee')
                ));
        } catch (\Watson\Validating\ValidationException $ex) {
            $errors = $ex->getErrors();
        }

        return Redirect::back()->withInput()->withErrors($errors, 'top');
    }

    public function edit($id){
        $services = Service::ofCurrentUser()->lists('name', 'id');
        $employee = Employee::find($id);
        return View::make('modules.as.employees.form', [
            'services' => $services,
            'employee' => $employee,
            'employeeId' => $employee->id
        ]);
    }

    public function doEdit(){
    }

    public function defaultTime($id){
        $defaultTime = Employee::find($id)->getDefaultTimes();
        return View::make('modules.as.employees.defaultTime', [
            'defaultTime' => $defaultTime,
            'employeeId' => $id
        ]);
    }

    public function updateDefaultTime(){
        $employeeId = Input::get('employee_id');
        $employee = Employee::find($employeeId);

        $types = EmployeeDefaultTime::getTypes();
        $input = Input::all();
        try{
            foreach ($types as $type) {
                $start_at = Carbon::createFromTime($input['start_hour'][$type],  $input['start_minute'][$type], 0, 'Europe/Helsinki');
                $end_at   = Carbon::createFromTime($input['end_hour'][$type],  $input['end_minute'][$type], 0, 'Europe/Helsinki');

                $is_day_off = false;
                if(isset($input['is_day_off'][$type])){
                    $is_day_off  = (bool) $input['is_day_off'][$type];
                }
                $data = [
                    'type' => $type,
                    'start_at' => $start_at,
                    'end_at' => $end_at,
                    'is_day_off' => $is_day_off
                ];

                $defaultTime  = EmployeeDefaultTime::where('employee_id', $employeeId)->where('type',$type)->first();
                if(empty($defaultTime)){
                    $defaultTime = new EmployeeDefaultTime;
                }
                $defaultTime->fill($data);
                $defaultTime->employee()->associate($employee);
                $defaultTime->save();
            }
        } catch(\Watson\Validating\ValidationException $ex){
             return Redirect::route('as.services.categories')
                ->with('messages', $this->successMessageBag($ex->getErrors()));
        }
          return Redirect::route('as.employees.defaultTime.get', array('id'=>$employeeId))
                ->with('messages', $this->successMessageBag(
                    trans('as.employees.success_save_default_time')
                ));
    }


    public function customTime(){

    }
}
