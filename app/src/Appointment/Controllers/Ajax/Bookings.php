<?php namespace App\Appointment\Controllers\Ajax;

use App, View, Redirect, Response, Request, Input, Config;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\AsConsumer;

class Bookings extends \App\Core\Controllers\Ajax\Base
{
      /**
     *  Handle ajax request to return services by certain employee and category
     *
     *  @return json
     **/
    public function getEmployeeServicesByCategory()
    {
        $categoryId = (int) Input::get('category_id');
        $employeeId = (int) Input::get('employee_id');
        $employee = Employee::ofCurrentUser()->find($employeeId);
        $services = $employee->services()->where('category_id', $categoryId)->get();
        $data = [];

        $data[-1] = [
            'id'   => -1,
            'name' => sprintf('-- %s --', trans('common.select'))
        ];

        foreach ($services as $service) {
            $data[$service->id] = [
                'id'   => $service->id,
                'name' => $service->name
            ];
        }

        return $this->json(array_values($data));
    }

     /**
     *  Handle ajax request to return service times in booking form
     *
     *  @return json
     **/
    public function getServiceTimes()
    {
        $serviceId    = (int) Input::get('service_id');
        $service      = Service::ofCurrentUser()->find($serviceId);
        $serviceTimes = $service->serviceTimes;
        $data = [];

        $data[-1] = [
            'id'          => -1,
            'name'        => sprintf('-- %s --', trans('common.select')),
            'length'      => 0,
            'description' => ''
        ];

        $data['default'] = [
            'id'          => 'default',
            'name'        => $service->length,
            'length'      => $service->length,
            'description' => $service->description
        ];
        foreach ($serviceTimes as $serviceTime) {
            $data[$serviceTime->id] = [
                'id'            => $serviceTime->id,
                'name'          => $serviceTime->length,
                'length'        => $serviceTime->length,
                'description'   => $serviceTime->description
            ];
        }

        return Response::json(array_values($data));
    }

}
