<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response, Util, Hashids;
use Carbon\Carbon, Cart, Session, Request;
use Illuminate\Support\Collection;
use App\Core\Models\User;
use App\Core\Models\Business;
use App\Consumers\Models\Consumer;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\AsConsumer;
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
        $inhouse         = (boolean) Input::get('inhouse', false);
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
        $service  = Service::find($serviceId);

        $employeeService = EmployeeService::where('employee_id', $employeeId)
                ->where('service_id', $serviceId)->first();
        $plustime = (!empty($employeeService)) ? $employeeService->plustime : 0;

        $length = 0;
        $during = 0;
        $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $bookingDate, $startTimeStr));
        $cartStartTime = $startTime->copy();

        $extraServiceTime  = 0;
        $extraServicePrice = 0;
        $extraServices     = [];

        if(!empty($extraServiceIds)){
            $extraServices = ExtraService::whereIn('id', $extraServiceIds)->get();
            foreach ($extraServices as $extraService) {
                $extraServiceTime   += $extraService->length;
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
        $endTime     = $startTime->copy()->addMinutes($lengthPlusExtraTime);
        $cartEndTime = $cartStartTime->copy()->addMinutes($duringPlusExtraTime);

        //Check is there any existed booking with this service time

        $isBookable = Booking::isBookable($employeeId, $bookingDate, $startTime, $endTime);
        //TODO Check overlapped booking in user cart

        //Check enough timeslot in employee default working time
        //Temporary the checking is removed

        if (!$isBookable) {
            $data['message'] = trans('as.bookings.error.add_overlapped_booking');
            return Response::json($data, 400);
        }

        $isResourcesAvailable = Booking::isResourcesAvailable($employeeId, $service, $bookingDate, $startTime, $endTime);

        if(!$isResourcesAvailable) {
            $data['message'] = trans('as.bookings.error.not_enough_resources');
            return Response::json($data, 400);
        }

        //TODO validate modify time and service time
        $bookingService = new BookingService();
        //Using uuid for retrieve it later when insert real booking
        $bookingServiceEndTime = $startTime->copy()->addMinutes($lengthPlusExtraTime);
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

            $bookingExtraService->extraService()->associate($extraService);
            $bookingExtraService->save();
        }

        $price = (!empty($serviceTime)) ? $serviceTime->price : $service->price;
        $price += $extraServicePrice;

        // Add to cart
        $bookingService->quantity = 1;
        $bookingService->price    = $price;

        $cart = ($inhouse)
            ? Cart::current()
            : null;

        if ($cart === null) {
            $cart = Cart::make(['status' => Cart::STATUS_INIT], $this->user);
        }
        $cart->addDetail($bookingService);

         $data = [
            'datetime'           => $startTime->toDateTimeString(),
            'price'              => $price,
            'service_name'       => $service->name,
            'employee_name'      => $employee->name,
            'uuid'               => $uuid,
            'cart_id'            => $cart->id,
            'booking_service_id' => $bookingService->id
        ];

        return Response::json($data);
    }

    public function addFrontEndBooking()
    {
        try {
            $hash                = Input::get('hash');
            $cartId              = Input::get('cart_id');
            $source              = Input::get('source', '');
            $cart                = Cart::find($cartId);
            $isRequestedEmployee = Input::get('is_requested_employee', false);
            $consumer = $cart->consumer;

            $length = 0;

            $decoded = Hashids::decrypt($hash);
            if(empty($decoded)){
                return;
            }
            $user = User::find($decoded[0]);

            foreach ($cart->details as $detail) {
                $bookingService = $detail->model->instance;
                $extraServices  = BookingExtraService::where('tmp_uuid', $bookingService->tmp_uuid)->get();

                $length   = $bookingService->calculcateTotalLength();
                $plustime = $bookingService->getEmployeePlustime();
                $date     = $bookingService->date;
                $start_at = $bookingService->start_at;
                $end_at   = with(new Carbon($start_at))->addMinutes($length);

                $price = $bookingService->calculcateTotalPrice();

                $booking = new Booking();
                $booking->fill([
                    'date'        => $date,
                    'start_at'    => $start_at,
                    'end_at'      => $end_at->toTimeString(),
                    'total'       => $length,
                    'status'      => Booking::STATUS_CONFIRM,
                    'uuid'        => $bookingService->tmp_uuid,
                    'total_price' => $price,
                    'modify_time' => $bookingService->modify_time,
                    'plustime'    => $plustime,
                    'source'      => $source,
                    'notes'       => $cart->notes,
                    'ip'          => Request::getClientIp()
                ]);

                $booking->consumer()->associate($consumer);
                $booking->user()->associate($user);
                $booking->employee()->associate($bookingService->employee);
                $booking->save();

                $bookingService->booking()->associate($booking);
                $bookingService->is_requested_employee = $isRequestedEmployee;
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

            $data['success'] = true;
            $data['message'] = trans('as.embed.success');
        } catch (\Watson\Validating\ValidationException $ex) {
            $data['success'] = false;
            $data['message'] =  Util::getHtmlListError($ex);
            return Response::json($data, 500);
        }
        return Response::json($data);
    }

    /**
     * This method reflects the addFrontEndBooking() above, but less strict. To
     * be frank, this method should not exist, really. Business logic should be
     * encapsulated inside Model, really.
     */
    public function addBookingFromCart()
    {
        $data = [
            'success' => true,
            'message' => trans('as.embed.success'),
        ];

        try {
            $cartId   = Input::get('cart_id');
            $cart     = Cart::find($cartId);
            $business = Business::findOrFail(Input::get('business_id'));
            $user     = $business->user;

            $bookingService = BookingService::findOrFail(Input::get('booking_service_id'));
            $length = 0;
            $service = (!empty($bookingService->serviceTime->id))
                ? $bookingService->serviceTime
                : $bookingService->service;
            $length += $service->length;

            $plustime = $bookingService->getEmployeePlustime();
            $length += $plustime;
            $bookingService->calculateExtraServices();

            //Plus extra service time
            $length += $bookingService->getExtraServiceTime();

            $date     = $bookingService->date;
            $start_at = $bookingService->start_at;
            $end_at   = with(new Carbon($bookingService->start_at))->addMinutes($length);

            $price = $service->price + $bookingService->getExtraServicePrice();

            $booking = new Booking();
            $booking->fill([
                'date'        => $date,
                'start_at'    => $start_at,
                'end_at'      => $end_at->toTimeString(),
                'total'       => $length,
                'uuid'        => $bookingService->tmp_uuid,
                'total_price' => $price,
                'modify_time' => $bookingService->modify_time,
                'plustime'    => $plustime,
                'notes'       => $cart->notes,
                'ip'          => Request::getClientIp()
            ]);

            $booking->user()->associate($user);
            $booking->employee()->associate($bookingService->employee);
            $booking->save();

            $bookingService->booking()->associate($booking);
            $bookingService->save();

            $extraServices  = BookingExtraService::where(
                'tmp_uuid',
                $bookingService->tmp_uuid
            )->get();

            foreach ($extraServices as $extraService) {
                $extraService->booking()->associate($booking);
                $extraService->save();
            }

            $data['booking_id'] = $booking->id;
        } catch (\Watson\Validating\ValidationException $ex) {
            $data['success'] = false;
            $data['message'] =  Util::getHtmlListError($ex);
            return Response::json($data, 500);
        }

        return Response::json($data);
    }
}
