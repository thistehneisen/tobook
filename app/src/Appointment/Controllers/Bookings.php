<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;

class Bookings extends AsBase
{
    public function getBookingForm(){
        $employeeId = Input::get('employee_id');
        $bookingDate = Input::get('booking_date');
        $bookingStatuses = Booking::getStatuses();

        $employee = Employee::find($employeeId);
        $services = $employee->services;
        $categories = [];
        $categories[-1] = trans('commom.select');
        foreach ($services as $service) {
            //for getting distinct categories
            $categories[$service->category->id] = $service->category->name;
        }
        return View::make('modules.as.bookings.form', [
            'bookingStatuses' => $bookingStatuses,
            'bookingDate' => $bookingDate,
            'categories' => $categories
        ]);
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
            'length' => sprintf('%s (%s)', $service->length, $service->description)
        ];
        foreach ($serviceTimes as $serviceTime) {
            $data[$serviceTime->id] = [
                'id'     => $serviceTime->id,
                'length' => $serviceTime->length
            ];
        }
        return Response::json(array_values($data));
    }

    public function addBookingService(){
        $serviceId   = Input::get('service_id');
        $employeeId  = Input::get('employee_id');
        $serviceTime = Input::get('service_time');
        $modifyTime  = Input::get('modify_time');
        $bookingDate = Input::get('booking_date');
        $startTime   = Input::get('start_time');
        $employee = Employee::find($employeeId);
        $service = Service::find($serviceId);
        //TODO check is there any existed booking
        $bookingService = new BookingTime;
        $bookingService->start_at = $start_time;
        $bookingService->employee()->associate($employee);
        $bookingService->service()->associate($service);
    }

    public function addBooking(){

    }
}
