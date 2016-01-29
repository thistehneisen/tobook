<?php namespace App\Appointment\Controllers\Ajax;

use App, View, Redirect, Response, Request, Input, Config, Session, Event;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use App\Appointment\Models\Reception\Rescheduler;
use App\Consumers\Models\Consumer;

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
        if (is_tobook()) {
            $_SESSION['cutId'] = $bookingId;
        }
        //Is there anything can raise error?
        return Response::json(['success' => true]);
    }

    public function discardCut()
    {
        Session::forget('cutId');
        if (is_tobook()) {
            unset($_SESSION['cutId']);
        }
        //Is there anything can raise error?
        return Response::json(['success' => true]);
    }

    public function paste()
    {
        $default      = (!empty($_SESSION['cutId'])) ? $_SESSION['cutId'] : null;
        $cutId        = Session::get('cutId', $default);
        $bookingDate  = str_standard_date(Input::get('booking_date'));
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
        } catch (\Exception $ex) {
            return Response::json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Return booking history of specific consumer
     *
     * @return View
     */
    public function getHistory()
    {
        $consumerId = Input::get('id');
        $page = Input::get('page');

        $history = Booking::ofCurrentUser()
            ->join('as_booking_services', 'as_bookings.id', '=', 'as_booking_services.booking_id')
            ->join('as_services', 'as_booking_services.service_id', '=', 'as_services.id')
            ->join('as_employees', 'as_booking_services.employee_id', '=', 'as_employees.id')
            ->where('as_bookings.consumer_id', $consumerId)
            ->select('as_bookings.id','as_employees.name as employee_name', 'as_booking_services.date', 'as_bookings.start_at', 'as_bookings.end_at', 'as_services.name', 'as_bookings.notes', 'as_bookings.created_at')
            ->distinct()
            ->orderBy('as_bookings.date', 'DESC');

        $perPage = 10;
        $items  = $history->paginate($perPage);

        return View::make('modules.as.bookings.history', [
            'consumer_id' => $consumerId,
            'history'     => $items,
        ]);
    }

    /**
     * Return consumer notes of a specific consumer
     *
     * @return string
     */
    public function getConsumerInfo()
    {
        $id = Input::get('id');
        $consumer = Consumer::find($id);
        $data = ['notes' => trans('common.empty') ];

        if (!empty($consumer->notes)) {
            $data['notes'] =  $consumer->notes;
        }

       return Response::json($data);
    }

}
