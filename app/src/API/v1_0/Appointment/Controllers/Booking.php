<?php namespace App\API\v1_0\Appointment\Controllers;

use Confide, Input, Request, Response, Util;
use App\Appointment\Models\Employee;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\AsConsumer;
use Watson\Validating\ValidationException;

class Booking extends Base
{
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        return $this->_storeOrUpdate();
    }


    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
            // consumer
            'first_name' => Input::get('first_name'),
            'last_name' => Input::get('last_name'),
            'email' => Input::get('email'),
            'phone' => Input::get('phone'),
            'address' => Input::get('address'),
            'hash' => Input::get('hash'),

            // booking service
            'booking_date' => Input::get('booking_date'),
            'modify_times' => Input::get('modify_times'),
            'service_time' => Input::get('service_time', 'default'),
            'start_time' => Input::get('start_time'),

            // booking
            'notes' => Input::get('notes'),
            'status' => Input::get('status', 'confirmed'),
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

            $consumer = AsConsumer::handleConsumer($input, $user);
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
