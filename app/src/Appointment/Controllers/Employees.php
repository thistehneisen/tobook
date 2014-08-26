<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config;
use App\Appointment\Models\Employee;

class Employees extends AsBase
{
    use App\Appointment\Traits\Crud;

    protected $viewPath = 'modules.as.employees';
    protected $langPrefix = 'as.employees';

    public function upsert()
    {
         $services = $this->serviceModel
            ->ofCurrentUser()->lists('name', 'id');
        return View::make('modules.as.employees.form', [
                'services' => $services
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
}
