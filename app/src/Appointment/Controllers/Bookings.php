<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response, Util, Hashids, Session;
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
    public function getBookingForm()
    {
        $employeeId = (int) Input::get('employee_id');
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
    public function getEmployeeServicesByCategory()
    {
        $categoryId = (int) Input::get('category_id');
        $employeeId = (int) Input::get('employee_id');
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
    public function getServiceTimes()
    {
        $serviceId    = (int) Input::get('service_id');
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
    public function addBookingService()
    {
        $serviceId      = (int) Input::get('service_id');
        $employeeId     = (int) Input::get('employee_id');
        $serviceTimeId  = Input::get('service_time', 'default');
        $modifyTime     = (int) Input::get('modify_times', 0);
        $hash           = Input::get('hash');
        $bookingDate    = Input::get('booking_date');
        $startTimeStr   = Input::get('start_time');
        $uuid           = Input::get('uuid', Util::uuid());

        //Use for front-end booking
        if(empty($this->user)){
            //TODO check if is there any potential error
            $decoded = Hashids::decrypt($hash);
            $this->user = User::find($decoded[0]);
        }

        $employee = Employee::find($employeeId);
        $service = Service::find($serviceId);

        $length = 0;
        if ($serviceTimeId === 'default') {
            $service = Service::find($serviceId);
            $length = $service->length;
        } else {
            $serviceTime = ServiceTime::find($serviceTimeId);
            $length = $serviceTime->length;
        }

        $endTimeDelta = ($length + $modifyTime);
        $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $bookingDate, $startTimeStr));
        $endTime = with(clone $startTime)->addMinutes($endTimeDelta);

        //Check is there any existed booking with this service time
        $bookings = Booking::where('date', $bookingDate)
            ->where('employee_id', $employeeId)
            ->where(function ($query) use ($startTime, $endTime) {
                return $query->where(function ($query) use ($startTime) {
                    return $query->where('start_at', '<=', $startTime->toTimeString())
                         ->where('end_at', '>', $startTime->toTimeString());
                })->orWhere(function ($query) use ($endTime) {
                     return $query->where('start_at', '<', $endTime->toTimeString())
                          ->where('end_at', '>=', $endTime->toTimeString());
                })->orWhere(function ($query) use ($startTime, $endTime) {
                     return $query->where('start_at', '=', $startTime->toTimeString())
                          ->where('end_at', '=', $endTime->toTimeString());
                });
            })->get();
        //TODO Check overlapped booking in user cart

        if (!$bookings->isEmpty()) {
            $data['message'] = trans('as.bookings.error.add_overlapped_booking');

            return Response::json($data, 400);
        }
        //TODO validate modify time and service time
        $bookingService = new BookingService();
        //Using uuid for retrieve it later when insert real booking
        $bookingService->fill([
            'tmp_uuid' => $uuid,
            'date'     => $bookingDate,
            'start_at' => $startTimeStr,
            'end_at'   => $endTime
        ]);

        if (!empty($serviceTime)) {
            $bookingService->serviceTime()->associate($serviceTime);
        }
        $bookingService->service()->associate($service);
        $bookingService->user()->associate($this->user);
        $bookingService->employee()->associate($employee);
        $bookingService->save();
        $price = isset($service) ? $service->price : $serviceTime->price;

        $data = [
            'datetime'      => $startTime->toDateTimeString(),
            'price'         => $price,
            'service_name'  => $service->name,
            'employee_name' => $employee->name,
            'uuid'          => $uuid
        ];

        $cart = [
            'datetime'      => $startTime->toDateString(),
            'price'         => $price,
            'service_name'  => $service->name,
            'employee_name' => $employee->name,
            'start_at'      => $startTimeStr,
            'end_at'        => $endTime->format('H:i'),
            'uuid'          => $uuid
        ];

        $carts = Session::get('carts', []);
        $carts[$uuid] = $cart;
        Session::put('carts' , $carts);

        return Response::json($data);
    }

    /**
     * Add new booking to database
     *
     **/
    public function addBooking()
    {
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

            $booking = new Booking();
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
            Session::forget('carts');
        } catch (\Exception $ex) {
            $data['status'] = false;
            $data['message'] = $ex->getMessage();
        }

        return Response::json($data);
    }

    public function addFrontEndBooking()
    {
        try {
            $carts = Session::get('carts');
            $hash  =  Input::get('hash');
            $consumer = $this->handleConsumer();

            $decoded = Hashids::decrypt($hash);
            if(empty($decoded)){
                return;
            }
            $user = User::find($decoded[0]);

            foreach ($carts as $item) {
                $uuid = $item['uuid'];

                $bookingService = BookingService::where('tmp_uuid', $uuid)->firstOrFail();

                $length = (isset($bookingService->serviceTime))
                    ? $bookingService->serviceTime->length
                    : $bookingService->service->length;

                $booking = new Booking();
                $booking->fill([
                    'date'      => $bookingService->date,
                    'start_at'  => $bookingService->start_at,
                    'end_at'    => $bookingService->end_at,
                    'total'     => $length,
                    'status'    => Booking::STATUS_CONFIRM,
                    'uuid'      => $uuid
                ]);
                //need to update end_at, total when add extra service

                $booking->consumer()->associate($consumer);
                $booking->user()->associate($user);
                $booking->employee()->associate($bookingService->employee);
                $booking->save();
                $bookingService->booking()->associate($booking);
                $bookingService->save();
            }
            Session::forget('carts');
            $data['status']      = true;
        } catch (\Exception $ex) {
            $data['status'] = false;
            $data['message'] = $ex->getMessage();
            return Response::json($data, 500);
        }
        return Response::json($data);
    }

    /**
     * Insert new consumer if not exist in db yet
     *
     * @return AsConsumer
     */
    private function handleConsumer()
    {
        //TODO suggest user info in front end

        //Insert customer
        $firstname = Input::get('firstname', '');
        $lastname  = Input::get('lastname', '');
        $email     = Input::get('email', '');
        $phone     = Input::get('phone', '');
        $address   = Input::get('address', '');
        $consumer = Consumer::where('email', $email)->first();
        $asConsumer = new AsConsumer();

        //TODO handle consumer validation
        if ($consumer === null) {
            $consumer = Consumer::make([
                'first_name' => $firstname,
                'last_name'  => $lastname,
                'email'      => $email,
                'phone'      => $phone,
                'address'    => $address
            ]);

            $asConsumer = new AsConsumer();
            $asConsumer->user()->associate($this->user);
            $asConsumer->consumer()->associate($consumer);
            $asConsumer->save();
        } else {
            $asConsumer = AsConsumer::where('consumer_id', $consumer->id)->first();
        }

        return $asConsumer;
    }

    /**
     * Handle ajax request to delete given booking service
     * that was added to booking form
     *
     * @return json
     */
    public function removeBookingService()
    {
        $uuid = Input::get('uuid');
        try {
            $bookingService = BookingService::where('tmp_uuid', $uuid)->delete();
            $data['success'] = true;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();

            return Response::json($data, 400);
        }

        return Response::json($data);
    }
}
