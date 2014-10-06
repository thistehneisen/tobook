<?php namespace App\Appointment\Controllers\Ajax;

use App, View, Redirect, Response, Request, Input, Config, Session;
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
use Carbon\Carbon;

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

        $data = $service->getServiceTimesData();

        return Response::json($data);
    }

    public function cut()
    {
        $bookingId = (int) Input::get('booking_id');
        Session::put('cutId' , $bookingId);
        //Is there anything can raise error?
        return Response::json(['success' => true]);
    }

    public function discardCut()
    {
        Session::forget('cutId');
        //Is there anything can raise error?
        return Response::json(['success' => true]);
    }

    public function paste()
    {
        $cutId        = Session::get('cutId', null);
        $bookingDate  = Input::get('booking_date');
        $startTimeStr = Input::get('start_time');
        $employeeId   = (int) Input::get('employee_id');

        if (empty($cutId)) {
            return Response::json(['success' => false]);
        }

        $booking = Booking::find($cutId);
        $bookingService   = $booking->bookingServices()->first();
        $bookingServiceId = $bookingService->id;
        $serviceId        = $bookingService->service->id;
        //Check if employee serve that service in booking and employee plustime
        $employee = Employee::find($employeeId);
        $employeeService = $employee->services()->where('service_id', $serviceId)->first();

        if (empty($employeeService)) {
            return Response::json([
                'success' => false,
                'message' => trans('as.bookings.error.employee_not_servable')
            ]);
        }

        $plustime = $employee->getPlustime($serviceId);
        $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $bookingDate, $startTimeStr));
        //Each emloyee has different plustime;
        $total = $booking->total - $booking->plustime + $plustime;
        $endTime   = with(clone $startTime)->addMinutes($total);

        //Check if there are enough slots for the cut booking
        $isBookable = Booking::isBookable($employeeId, $bookingDate, $startTime, $endTime);

        if (!$isBookable) {
            return Response::json([
                'success' => false,
                'message' => trans('as.bookings.error.add_overlapped_booking')
            ]);
        }

        $booking->start_at = $startTime->toTimeString();
        $booking->end_at   = $endTime->toTimeString();
        $booking->date     = $startTime->toDateString();
        $booking->employee()->associate($employee);
        $booking->save();
        $bookingService->employee()->associate($employee);
        $bookingService->date = $startTime->toDateString();
        $bookingService->save();
        Session::forget('cutId');
        return Response::json(['success' => true]);
    }

}
