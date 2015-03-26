<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, NAT, Closure, Response;
use Carbon\Carbon;
use App\Core\Models\User;
use App\Consumers\Models\Consumer;
use App\Appointment\Models\Employee;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\Option;

class FlashDeal extends AsBase
{
    public function getFlashDealForm()
    {
        $employeeId  = (int) Input::get('employee_id');
        $bookingDate = Input::get('booking_date');
        $startTime   = Input::get('start_time');

        $employee = Employee::ofCurrentUser()->find($employeeId);
        $services = $employee->services->lists('name', 'id');

        $master_categories = ['-1' => trans('common.select')];
        $master_categories += MasterCategory::lists('name','id');

        return View::make('modules.as.flashdeal.form', [
            'employee'          => $employee,
            'master_categories' => $master_categories,
            'services'          => $services,
            'bookingDate'       => $bookingDate,
            'startTime'         => $startTime
        ]);
    }

    public function getServices()
    {
        $masterCategoryId  = (int) Input::get('master_category_id');
        $employeeId  = (int) Input::get('employee_id');
        $employee = Employee::ofCurrentUser()->find($employeeId);

        $data[-1] = [
            'id'   => -1,
            'name' => sprintf('-- %s --', trans('common.select'))
        ];

        $services = $employee->services->filter(function($service) use ($masterCategoryId){
            if(!empty($service->masterCategory->id)) {
                if($service->masterCategory->id == $masterCategoryId) {
                    return $service;
                }
            }
        });


        foreach ($services as $service) {
            $data[$service->id] = [
                'id'    => $service->id,
                'name'  => $service->name,
                'price' => $service->price
            ];
        }

        return Response::json(array_values($data));
    }
}
