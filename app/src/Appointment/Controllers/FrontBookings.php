<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response, Util, Hashids, Session, Request;
use Illuminate\Support\Collection;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\AsConsumer;
use App\Consumers\Models\Consumer;
use App\Core\Models\User;
use Carbon\Carbon;
use App\Appointment\Models\Observer\EmailObserver;
use App\Appointment\Models\Observer\SmsObserver;

class FrontBookings extends Bookings
{
    //Use this controller to avoid authentication in Bookings

    //TODO create addFrontEndBookingService to handle Extra service

      /**
     * Add new temp service to booking
     *
     * @return json
     */
    public function addBookingService()
    {
        $serviceId      = (int) Input::get('service_id');
        $employeeId     = (int) Input::get('employee_id');
        $modifyTime     = (int) Input::get('modify_times', 0);
        $serviceTimeId  = Input::get('service_time', 'default');
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
        $during = 0;
        $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $bookingDate, $startTimeStr));
        if ($serviceTimeId === 'default') {
            $service = Service::find($serviceId);
            $length = $service->length;
            $during = $service->during;
            $startTime->subMinutes($service->before);
        } else {
            $serviceTime = ServiceTime::find($serviceTimeId);
            $length = $serviceTime->length;
            $during = $serviceTime->during;
            $startTime->subMinutes($serviceTime->before);
        }

        $endTime = with(clone $startTime)->addMinutes($length);

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

        //Check enough timeslot in employee default working time
        list($endHour, $endMinute) = explode(':', $employee->getTodayDefaultEndAt($startTime->dayOfWeek));
        $endAt = with(clone $endTime)->setTime($endHour, $endMinute, 0);
        if ($endTime > $endAt) {
            $data['message'] = trans('as.bookings.error.insufficient_slots');
            return Response::json($data, 400);
        }

        if (!$bookings->isEmpty()) {
            $data['message'] = trans('as.bookings.error.add_overlapped_booking');
            return Response::json($data, 400);
        }
        //TODO validate modify time and service time
        $bookingService = new BookingService();
        //Using uuid for retrieve it later when insert real booking
        $bookingService->fill([
            'tmp_uuid'    => $uuid,
            'date'        => $bookingDate,
            'modify_time' => $modifyTime,
            'start_at'    => $startTime->toTimeString(),
            'end_at'      => $endTime
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
            'end_at'        => with(clone $startTime)->addMinutes($during)->format('H:i'),
            'uuid'          => $uuid
        ];

        $carts = Session::get('carts', []);
        $carts[$uuid] = $cart;
        Session::put('carts' , $carts);

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

                $length = (!empty($bookingService->serviceTime))
                    ? $bookingService->serviceTime->length
                    : $bookingService->service->length;

                $booking = new Booking();
                $booking->fill([
                    'date'        => $bookingService->date,
                    'start_at'    => $bookingService->start_at,
                    'end_at'      => $bookingService->end_at,
                    'total'       => $length,
                    'status'      => Booking::STATUS_CONFIRM,
                    'uuid'        => $uuid,
                    'modify_time' => $bookingService->modify_time,
                    'ip'          => Request::getClientIp()
                ]);
                //need to update end_at, total when add extra service

                $booking->consumer()->associate($consumer);
                $booking->user()->associate($user);
                $booking->employee()->associate($bookingService->employee);
                $booking->save();

                $bookingService->booking()->associate($booking);
                $bookingService->save();

                //Send notification email and SMSs
                $booking->attach(new EmailObserver());
                $booking->attach(new SmsObserver());
                $booking->notify();
            }

            Session::forget('carts');
            Session::forget('booking_info');

            $data['status']      = true;
        } catch (\Exception $ex) {
            $data['status'] = false;
            $data['message'] = $ex->getMessage();
            return Response::json($data, 500);
        }
        return Response::json($data);
    }
}
