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
        $startTimeStr    = trim(Input::get('start_time'));
        $uuid            = Input::get('uuid', Util::uuid());

        //Use for front-end booking
        if(empty($this->user)){
            //TODO check if is there any potential error
            $decoded = Hashids::decrypt($hash);
            $this->user = User::find($decoded[0]);
        }

        try{
            $receptionist = new FrontendReceptionist();
            $receptionist->setBookingId(null)
                ->setUUID($uuid)
                ->setUser($this->user)
                ->setBookingDate($bookingDate)
                ->setStartTime($startTimeStr)
                ->setServiceId($serviceId)
                ->setEmployeeId($employeeId)
                ->setServiceTimeId($serviceTimeId)
                ->setModifyTime($modifyTime);

            $bookingService = $receptionist->upsertBookingService();
        } catch(\Exception $ex) {
            $data['message'] = $ex->getMessage();
            return Response::json($data, 400);
        }

        $cart = ($inhouse)
            ? Cart::current()
            : null;

        if ($cart === null) {
            $cart = Cart::make(['status' => Cart::STATUS_INIT], $this->user);
        }
        $cart->addDetail($bookingService);

        $data = [
            'datetime'           => $bookingService->plainStartTime->toDateTimeString(),
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

            // Complete the cart
            $cart->complete();

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
            $source   = Input::get('source', 'inhouse');
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
                'source'      => $source,
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

            // Update cart status
            $cart->complete();

            $data['booking_id'] = $booking->id;
        } catch (\Watson\Validating\ValidationException $ex) {
            $data['success'] = false;
            $data['message'] =  Util::getHtmlListError($ex);
            return Response::json($data, 500);
        }

        return Response::json($data);
    }
}
