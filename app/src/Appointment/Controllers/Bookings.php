<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;

class Bookings extends AsBase
{
    public function getEmployeeCategories(){
        $employeeId = Input::get('employee_id');
        $employee = Employee::find($employeeId);
        $services = $employee->services;
        $categories = [];
        foreach ($services as $service) {
            //for getting distinct categories
            $categories[$service->category->id] = [
                'id'   => $service->category->id,
                'name' => $service->category->name
            ];
        }
        return Response::json(array_values($categories));
    }

    public function getEmployeeServicesByCategory(){
        $categoryId = Input::get('category_id');
        $employeeId = Input::get('employee_id');
        $employee = Employee::find($employeeId);
        $services = $employee->services()->where('category_id', $categoryId)->get();
        $data = [];
        foreach ($services as $service) {
            $data[$service->id] = [
                'id'   => $service->id,
                'name' => $service->name
            ];
        }
        return Response::json(array_values($data));
    }

    public function getServiceTimes(){
        $serviceId = Input::get('service_id');
        $service = Service::find($serviceId);
        $serviceTimes = $service->serviceTimes;
        $data = [];
        $data['default'] = [
            'id' => 'default',
            'length' => $service->length
        ];
        foreach ($serviceTimes as $serviceTime) {
            $data[$serviceTime->id] = [
                'id'     => $serviceTime->id,
                'length' => $serviceTime->length
            ];
        }
        return Response::json(array_values($data));
    }
}
