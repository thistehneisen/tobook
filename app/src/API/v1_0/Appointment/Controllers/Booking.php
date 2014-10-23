<?php namespace App\API\v1_0\Appointment\Controllers;

use App\Appointment\Models\Consumer;
use Confide, Input, Request, Response, Util;
use App\Appointment\Models\Employee;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\AsConsumer;
use Watson\Validating\ValidationException;

class Booking extends Base
{
    /**
     * Store a newly created booking.
     *
     * @return Response
     */
    public function store()
    {
        return $this->_storeOrUpdate();
    }


    /**
     * Display the specified booking.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $booking = \App\Appointment\Models\Booking::ofCurrentUser()->findOrFail($id);

        return Response::json([
            'error' => false,
            'data' => $this->_prepareBookingData($booking),
        ], 200);
    }


    /**
     * Update the specified booking.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $existingBooking = \App\Appointment\Models\Booking::ofCurrentUser()->findOrFail($id);
        return $this->_storeOrUpdate($existingBooking);
    }


    /**
     * Delete the specified booking.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $booking = \App\Appointment\Models\Booking::ofCurrentUser()->findOrFail($id);

        $booking->delete();

        return Response::json([
            'error' => false,
            'message' => 'Booking has been canceled.',
        ], 200);
    }

    /**
     * Change status of the specified booking.
     *
     * @param int $id
     * @return Response
     */
    public function putStatus($id)
    {
        $booking = \App\Appointment\Models\Booking::ofCurrentUser()->findOrFail($id);

        $booking->status = \App\Appointment\Models\Booking::getStatus(Input::get('booking_status'));

        try {
            $booking->saveOrFail();

            return Response::json([
                'error' => false,
                'data' => $this->_prepareBookingData($booking),
            ], 200);
        } catch (ValidationException $e) {
            return Response::json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Change modify_time of the specified booking.
     * This method only supports booking with one service for now.
     *
     * @param int $id
     * @return Response
     */
    public function putModifyTime($id)
    {
        $booking = \App\Appointment\Models\Booking::ofCurrentUser()->findOrFail($id);
        if ($booking->bookingServices()->count() != 1) {
            return Response::json([
                'error' => true,
                'message' => trans('as.bookings.modify_time_one_service_booking_only')
            ], 400);
        } else {
            // TODO support multiple services per booking
            $bookingService = $booking->bookingServices()->first();
        }

        try {
            $input = [
                // booking service
                'booking_date' => $bookingService->date,
                'modify_time' => Input::get('modify_time'),
                'service_time' => (!empty($bookingService->service_time_id) ? $bookingService->serviceTime->id : 'default'),
                'start_time' => $bookingService->start_at,
            ];

            BookingService::saveBookingService($booking->uuid, $booking->employee, $bookingService->service, $input, $bookingService);

            \App\Appointment\Models\Booking::updateBooking($booking);

            return Response::json([
                'error' => false,
                'data' => $this->_prepareBookingData($booking),
            ], 200);
        } catch (ValidationException $e) {
            return Response::json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Change schedule of the specified booking.
     *
     * @param int $id
     * @return Response
     */
    public function putSchedule($id)
    {
        $booking = \App\Appointment\Models\Booking::ofCurrentUser()->findOrFail($id);

        $employeeId = intval(Input::get('employee_id'));
        $employee = Employee::ofCurrentUser()->findOrFail($employeeId);

        $input = [
            'booking_date' => Input::get('booking_date'),
            'start_time' => Input::get('start_time'),
        ];

        try {
            \App\Appointment\Models\Booking::rescheduleBooking($booking, $employee, $input);

            return Response::json([
                'error' => false,
                'data' => $this->_prepareBookingData($booking),
            ], 200);
        } catch (ValidationException $e) {
            return Response::json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    protected function _storeOrUpdate(\App\Appointment\Models\Booking $existingBooking = null)
    {
        // TODO: support multiple services per booking
        $user = Confide::user();

        $employeeId = intval(Input::get('employee_id'));
        $employee = Employee::ofCurrentUser()->findOrFail($employeeId);

        $serviceId = intval(Input::get('service_id'));
        $service = $employee->services()->findOrFail($serviceId);

        $extraServiceIds = Input::get('extra_services', []);
        $extraServices = [];
        foreach ($extraServiceIds as $extraServiceId) {
            $extraServices[] = $service->extraServices()->findOrFail($extraServiceId);
        }

        $consumerId = intval(Input::get('consumer_id'));
        $consumer = Consumer::ofCurrentUser()->findOrFail($consumerId);

        $existingBookingService = null;
        $existingExtraServiceIds = [];
        $newExtraServices = [];
        $needDeletedBookingExtraServices = [];
        if (!empty($existingBooking)) {
            $uuid = $existingBooking->uuid;
            $existingBookingService = $existingBooking->bookingServices()->first();

            foreach ($existingBooking->extraServices as $existingExtraService) {
                if (in_array($existingExtraService->extra_service_id, $extraServiceIds)) {
                    // existing and should be kept
                    $existingExtraServiceIds[] = $existingExtraService->extra_service_id;
                } else {
                    // existing and should be deleted
                    $needDeletedBookingExtraServices[] = $existingExtraService;
                }
            }
            foreach ($extraServices as $extraService) {
                if (!in_array($extraService->id, $existingExtraServiceIds)) {
                    // new extra service
                    $newExtraServices[] = $extraService;
                }
            }
        } else {
            $uuid = Util::uuid();

            foreach ($extraServices as $extraService) {
                // all extra services are new
                $newExtraServices[] = $extraService;
            }
        }

        $input = [
            // booking service
            'booking_date' => Input::get('booking_date'),
            'modify_time' => Input::get('modify_time'),
            'service_time' => Input::get('service_time', 'default'),
            'start_time' => Input::get('start_time'),

            // booking
            'notes' => Input::get('booking_notes'),
            'status' => Input::get('booking_status', 'confirmed'),
            'ip' => Request::getClientIp(),
        ];

        try {
            foreach ($needDeletedBookingExtraServices as $bookingExtraService) {
                $bookingExtraService->delete();
            }

            $bookingService = BookingService::saveBookingService($uuid, $employee, $service, $input, $existingBookingService);

            foreach ($newExtraServices as $extraService) {
                BookingExtraService::addExtraService($uuid, $employee, $bookingService, $extraService);
            }

            $booking = \App\Appointment\Models\Booking::saveBooking($uuid, $user, $consumer, $input, $existingBooking);

            return Response::json([
                'error' => false,
                'data' => $this->_prepareBookingData($booking),
            ], 200);
        } catch (\InvalidArgumentException $e) {
            return Response::json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        } catch (ValidationException $e) {
            return Response::json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
