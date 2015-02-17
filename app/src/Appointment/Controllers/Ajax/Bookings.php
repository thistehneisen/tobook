<?php namespace App\Appointment\Controllers\Ajax;

use App, View, Redirect, Response, Request, Input, Config, Session, Event;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Reception\Rescheduler;
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
                'id'    => $service->id,
                'name'  => $service->name,
                'price' => $service->price
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
        $serviceId = (int) Input::get('service_id');
        $employeeId = (int) Input::get('employee_id');
        $service = Service::ofCurrentUser()->find($serviceId);
        $data = $service->getServiceTimesData($employeeId);

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
            return Response::json([
                'success' => false,
                'message' => trans('as.bookings.error.booking_not_found')
            ]);
        }

        try {
            $booking = Booking::findOrFail($cutId);
            $receptionist = new Rescheduler();
            $receptionist->setBookingId($cutId)
                ->setUUID($booking->uuid)
                ->setUser($this->user)
                ->setEmployeeId($employeeId)
                ->setBookingDate($bookingDate)
                ->setStartTime($startTimeStr);

            $booking = $receptionist->reschedule();

            Event::fire('employee.calendar.invitation.send', [$booking]);
            Session::forget('cutId');
            return Response::json(['success' => true]);
        } catch(\Exception $ex){
            return Response::json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }

}
