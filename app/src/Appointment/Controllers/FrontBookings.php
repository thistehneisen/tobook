<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response, Util, Hashids, Event;
use Carbon\Carbon, Cart, Session, Request;
use App\Core\Models\User;
use App\Core\Models\Business;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use App\Appointment\Models\Observer\EmailObserver;
use App\Appointment\Models\Observer\SmsObserver;
use App\Appointment\Models\Reception\FrontendReceptionist;

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
        $uuid            = Input::get('uuid', Booking::uuid());

        //TODO check if is there any potential error
        //Always get user from hash in front end
        $decoded = Hashids::decrypt($hash);
        $this->user = User::find($decoded[0]);

        try {
            $receptionist = new FrontendReceptionist();
            $receptionist->setBookingId(null)
                ->setUUID($uuid)
                ->setUser($this->user)
                ->setBookingDate($bookingDate)
                ->setStartTime($startTimeStr)
                ->setServiceId($serviceId)
                ->setEmployeeId($employeeId)
                ->setServiceTimeId($serviceTimeId)
                ->setExtraServiceIds($extraServiceIds)
                ->setModifyTime($modifyTime);

            $bookingService = $receptionist->upsertBookingService();
        } catch (\Exception $ex) {
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
            'price'              => $bookingService->calculcateTotalPrice(),
            'service_name'       => $bookingService->service->name,
            'employee_name'      => $bookingService->employee->name,
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
            $consumer            = $cart->consumer;

            $length = 0;

            $decoded = Hashids::decrypt($hash);
            if (empty($decoded)) {
                return;
            }
            $user = User::find($decoded[0]);

            foreach ($cart->details as $detail) {
                $bookingService = $detail->model->instance;

                $receptionist = new FrontendReceptionist();
                $receptionist->setBookingId(null)
                    ->setUUID($bookingService->tmp_uuid)
                    ->setUser($user)
                    ->setNotes($cart->notes)
                    ->setIsRequestedEmployee($isRequestedEmployee)
                    ->setConsumer($consumer)
                    ->setClientIP(Request::getClientIp())
                    ->setSource($source);

                $booking = $receptionist->upsertBooking();
            }

            // Complete the cart
            if ($source !== 'inhouse' && !empty($booking)) {
                $cart->complete();
            }

            $data['success'] = true;
            $data['message'] = (Input::get('l') === '3' && $source === 'inhouse')
                ? trans('as.embed.success_simple')
                : trans('as.embed.success');
        } catch (\Exception $ex) {
            $data['success'] = false;
            $data['message'] = trans('common.err.unexpected');

            return Response::json($data, 500);
        }

        //TODO maybe change to use Event instead of Observer
        if ($source !== 'inhouse' && !empty($booking)) {
            //Send notification email and SMSs
            try {
                $booking->attach(new EmailObserver());
                $booking->attach(new SmsObserver());
                $booking->notify();
                //Send calendar invitation to employee
                Event::fire('employee.calendar.invitation.send', [$booking]);
            } catch (\Exception $ex) {
                \Log::warning('Could not send sms or email:' . $ex->getMessage());
            }
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
            $cartId         = Input::get('cart_id');
            $source         = Input::get('source', 'inhouse');
            $cart           = Cart::find($cartId);
            $business       = Business::findOrFail(Input::get('business_id'));
            $bookingService = BookingService::findOrFail(Input::get('booking_service_id'));
            $user           = $business->user;

            //The main different with addFrontEndBooking is: no $consumer
            $receptionist = new FrontendReceptionist();
            $receptionist->setBookingId(null)
                ->setUUID($bookingService->tmp_uuid)
                ->setUser($user)
                ->setNotes($cart->notes)
                ->setIsRequestedEmployee(false)
                ->setClientIP(Request::getClientIp())
                ->setSource($source);

            $booking = $receptionist->upsertBooking();

            // Complete the cart
            if ($source !== 'inhouse' && !empty($booking)) {
                $cart->complete();
            }

            $data['booking_id'] = $booking->id;
        } catch (\Exception $ex) {
            $data['success'] = false;
            $data['message'] = ($ex instanceof \Watson\Validating\ValidationException)
                ? Util::getHtmlListError($ex)
                : trans('common.err.unexpected');

            return Response::json($data, 500);
        }

        return Response::json($data);
    }
}
