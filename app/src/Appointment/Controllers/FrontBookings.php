<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response, Util, Hashids, Session, Request;
use Illuminate\Support\Collection;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeService;
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
        $serviceId       = (int) Input::get('service_id');
        $employeeId      = (int) Input::get('employee_id');
        $modifyTime      = (int) Input::get('modify_times', 0);
        $serviceTimeId   = Input::get('service_time', 'default');
        $extraServiceIds = Input::get('extra_services', []);
        $hash            = Input::get('hash');
        $bookingDate     = Input::get('booking_date');
        $startTimeStr    = Input::get('start_time');
        $uuid            = Input::get('uuid', Util::uuid());

        //Use for front-end booking
        if(empty($this->user)){
            //TODO check if is there any potential error
            $decoded = Hashids::decrypt($hash);
            $this->user = User::find($decoded[0]);
        }

        $employee = Employee::find($employeeId);
        $service = Service::find($serviceId);

        $employeeService = EmployeeService::where('employee_id', $employeeId)
                ->where('service_id', $serviceId)->first();
        $plustime = (!empty($employeeService)) ? $employeeService->plustime : 0;

        $length = 0;
        $during = 0;
        $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $bookingDate, $startTimeStr));

        $extraServiceTime = 0;
        $extraServicePrice = 0;
        $extraServices = [];

        if(!empty($extraServiceIds)){
            $extraServices = ExtraService::whereIn('id', $extraServiceIds)->get();
            foreach ($extraServices as $extraService) {
                $extraServiceTime += $extraService->length;
                $extraServicePrice  += $extraService->price;
            }
        }
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

        $lengthPlusExtraTime = $length + $extraServiceTime + $plustime;
        $duringPlusExtraTime = $during + $extraServiceTime + $plustime;
        $endTime = with(clone $startTime)->addMinutes($lengthPlusExtraTime);

        //Check is there any existed booking with this service time

        $isBookable = Booking::isBookable($employeeId, $bookingDate, $startTime, $endTime);
        //TODO Check overlapped booking in user cart

        //Check enough timeslot in employee default working time
         //Temporary the checking is removed

        if (!$isBookable) {
            $data['message'] = trans('as.bookings.error.add_overlapped_booking');
            return Response::json($data, 400);
        }
        //TODO validate modify time and service time
        $bookingService = new BookingService();
        //Using uuid for retrieve it later when insert real booking
        $bookingServiceEndTime = with(clone $startTime)->addMinutes($lengthPlusExtraTime);
        $bookingService->fill([
            'tmp_uuid'    => $uuid,
            'date'        => $bookingDate,
            'modify_time' => $modifyTime,
            'start_at'    => $startTime->toTimeString(),
            'end_at'      => $bookingServiceEndTime->toTimeString()
        ]);

        if (!empty($serviceTime)) {
            $bookingService->serviceTime()->associate($serviceTime);
        }
        $bookingService->service()->associate($service);
        $bookingService->user()->associate($this->user);
        $bookingService->employee()->associate($employee);
        $bookingService->save();

        foreach ($extraServices as $extraService) {
            $bookingExtraService = new BookingExtraService;
            $bookingExtraService->fill([
                'date'     => $bookingDate,
                'tmp_uuid' => $uuid
            ]);

            //$bookingExtraService->booking()->associate($booking);
            $bookingExtraService->extraService()->associate($extraService);
            $bookingExtraService->save();
        }

        $price = isset($service) ? $service->price : $serviceTime->price;
        $price += $extraServicePrice;

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
            'extra_services'=> $extraServiceIds,
            'employee_name' => $employee->name,
            'start_at'      => $startTime->format('H:i'),
            'end_at'        => with(clone $startTime)->addMinutes($duringPlusExtraTime)->format('H:i'),
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
            $length = 0;

            $decoded = Hashids::decrypt($hash);
            if(empty($decoded)){
                return;
            }
            $user = User::find($decoded[0]);

            foreach ($carts as $item) {
                $uuid = $item['uuid'];

                $bookingService = BookingService::where('tmp_uuid', $uuid)->firstOrFail();
                $extraServices  = BookingExtraService::where('tmp_uuid', $uuid)->get();

                $length = (!empty($bookingService->serviceTime))
                    ? $bookingService->serviceTime->length
                    : $bookingService->service->length;

                $employeeService = EmployeeService::where('employee_id', $bookingService->employee->id)
                    ->where('service_id', $bookingService->service->id)->first();
                $plustime = (!empty($employeeService)) ? $employeeService->plustime : 0;
                $length += $plustime;

                //Plus extra service time
                foreach ($extraServices as $extraService) {
                    $length += $extraService->length;
                }

                $date = $bookingService->date;
                $start_at = $bookingService->start_at;
                $end_at = with(new Carbon($bookingService->start_at))->addMinutes($length);

                $booking = new Booking();
                $booking->fill([
                    'date'        => $date,
                    'start_at'    => $start_at,
                    'end_at'      => $end_at->toTimeString(),
                    'total'       => $length,
                    'status'      => Booking::STATUS_CONFIRM,
                    'uuid'        => $uuid,
                    'total_price' => $item['price'],
                    'modify_time' => $bookingService->modify_time,
                    'plustime'    => $plustime,
                    'ip'          => Request::getClientIp()
                ]);

                $booking->consumer()->associate($consumer);
                $booking->user()->associate($user);
                $booking->employee()->associate($bookingService->employee);
                $booking->save();

                $bookingService->booking()->associate($booking);
                $bookingService->save();

                foreach ($extraServices as $extraService) {
                    $extraService->booking()->associate($booking);
                    $extraService->save();
                }

                //Send notification email and SMSs
                $booking->attach(new EmailObserver());
                $booking->attach(new SmsObserver());
                $booking->notify();
            }

            Session::forget('carts');
            Session::forget('booking_info');

            $data['success']      = true;
        } catch (\Exception $ex) {
            $data['success'] = false;
            $data['message'] = trans('as.bookings.error.unknown');
            return Response::json($data, 500);
        }
        return Response::json($data);
    }
}
