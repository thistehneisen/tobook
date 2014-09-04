<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response, Util;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\AsConsumer;
use App\Consumers\Models\Consumer;
use Carbon\Carbon;

class Bookings extends AsBase
{
    use App\Appointment\Traits\Crud;
    protected $viewPath   = 'modules.as.bookings';
    protected $langPrefix = 'as.bookings';

    /**
     * Handle ajax request to display booking form
     *
     * @return View
     **/
    public function getBookingForm(){
        $employeeId = Input::get('employee_id');
        $bookingDate = Input::get('booking_date');
        $startTime = Input::get('start_time');

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
            'uuid'            => Util::uuid(),
            'employee'        => $employee,
            'categories'      => $categories,
            'bookingDate'     => $bookingDate,
            'startTime'       => $startTime,
            'bookingStatuses' => $bookingStatuses
        ]);
    }

    /**
     *  Handle ajax request to return services by certain employee and category
     *
     *  @return json
     **/
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

    /**
     *  Handle ajax request to return service times in booking form
     *
     *  @return json
     **/
    public function getServiceTimes(){
        $serviceId    = Input::get('service_id');
        $service      = Service::find($serviceId);
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

    /**
     * Add new temp service to booking
     *
     * @return json
     */
    public function addBookingService(){
        $serviceId      = Input::get('service_id');
        $employeeId     = Input::get('employee_id');
        $serviceTimeId  = Input::get('service_time');
        $modifyTime     = Input::get('modify_times');
        $bookingDate    = Input::get('booking_date');
        $startTimeStr      = Input::get('start_time');
        $uuid           = Input::get('uuid');

        $employee = Employee::find($employeeId);
        $service = Service::find($serviceId);

        $length = 0;
        if($serviceTimeId === 'default'){
            $service = Service::find($serviceId);
            $length = $service->length;
        } else {
            $serviceTime = ServiceTime::find($serviceTimeId);
            $length = $serviceTime->length;
        }

        $endTimeDelta = ($length + $modifyTime);
        $startTime = Carbon::createFromFormat('H:i', $startTimeStr);
        $endTime = Carbon::createFromFormat('H:i', $startTimeStr)->addMinutes($endTimeDelta);

        //TODO check is there any existed booking with this service time
        $bookings = Booking::where('date', $bookingDate)
            ->where('employee_id', $employeeId)
            ->where(function($query) use($startTime, $endTime){
                return $query->where(function($query) use($startTime) {
                    return $query->where('start_at', '<=', $startTime->toTimeString())
                         ->where('end_at', '>', $startTime->toTimeString());
                })->orWhere(function($query) use($endTime) {
                     return $query->where('start_at', '<', $endTime->toTimeString())
                          ->where('end_at', '>=', $endTime->toTimeString());
                })->orWhere(function($query) use($startTime, $endTime) {
                     return $query->where('start_at', '=', $startTime->toTimeString())
                          ->where('end_at', '=', $endTime->toTimeString());
                });
            })->get();

        if(!$bookings->isEmpty()){
            $data['message'] = trans('as.bookings.error.add_overlapped_booking');
            return Response::json($data, 400);
        }
        //TODO validate modify time and service time
        $bookingService = new BookingService;
        //Using uuid for retrieve it later when insert real booking
        $bookingService->fill([
            'tmp_uuid' => $uuid,
            'date'     => $bookingDate,
            'start_at' => $startTimeStr,
            'end_at'   => $endTime
        ]);

        if(!empty($serviceTime)){
            $bookingService->serviceTime()->associate($serviceTime);
        }
        $bookingService->service()->associate($service);
        $bookingService->user()->associate($this->user);
        $bookingService->employee()->associate($employee);
        $bookingService->save();
        $price = isset($service) ? $service->price : $serviceTime->price;

        $data = [
            'datetime'      => sprintf('%s %s', $bookingDate, $startTime),
            'price'         => $price,
            'service_name'  => $service->name,
            'employee_name' => $employee->name
        ];

        return Response::json($data);
    }

    /**
     * Add new booking to database
     *
     **/
    public function addBooking(){
        $uuid          = Input::get('booking_uuid');
        $bookingId     = Input::get('booking_id');
        $bookingStatus = Input::get('booking_status');
        $notes         = Input::get('booking_notes');

        try {
            //support multiple service time?
            $bookingService = BookingService::where('tmp_uuid', $uuid)->firstOrFail();
            $data = [];

            $consumer = $this->handleConsumer();

            $length = (isset($bookingService->serviceTime))
                    ? $bookingService->serviceTime->length
                    : $bookingService->service->length;

            $status = Booking::getStatus($bookingStatus);

            $booking = new Booking;
            $booking->fill([
                'date'      => $bookingService->date,
                'start_at'  => $bookingService->start_at,
                'end_at'    => $bookingService->end_at,
                'total'     => $length,
                'status'    => $status,
                'notes'     => $notes,
                'uuid'      => $uuid
            ]);
            //need to update end_at, total when add extra service

            $booking->consumer()->associate($consumer);
            $booking->user()->associate($this->user);
            $booking->employee()->associate($bookingService->employee);
            $booking->save();
            $bookingService->booking()->associate($booking);
            $bookingService->save();
            $data['status']      = true;
            $data['baseURl']     = route('as.index');
            $data['bookingDate'] = $booking->date;
        } catch (\Exception $ex){
            $data['status'] = false;
            $data['message'] = $ex->getMessage();
        }
        return Response::json($data);
    }

    private function handleConsumer(){
        //Insert customer
        $firstname = Input::get('consumer_firstname');
        $lastname  = Input::get('consumer_lastname');
        $email     = Input::get('consumer_email');
        $phone     = Input::get('consumer_phone');
        $address   = Input::get('consumer_address');
        $consumer = Consumer::where('email', $email)->first();
        $asConsumer = new AsConsumer;

        //TODO handle consumer validation
        if($consumer === null){
            $consumer = Consumer::make([
                'first_name' => $firstname,
                'last_name'  => $lastname,
                'email'      => $email,
                'phone'      => $phone
            ]);

            $asConsumer = new AsConsumer;
            $asConsumer->user()->associate($this->user);
            $asConsumer->consumer()->associate($consumer);
            $asConsumer->save();
        } else {
            $asConsumer = AsConsumer::where('consumer_id', $consumer->id)->first();
        }
        return $asConsumer;
    }
}
