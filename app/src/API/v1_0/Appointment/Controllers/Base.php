<?php namespace App\API\v1_0\Appointment\Controllers;

use Config;
use App\Appointment\Models\Booking;
use Carbon\Carbon;

class Base extends \App\Core\Controllers\Base
{
    protected function _prepareEmployeeData($employee)
    {
        $employeeData = [
            'type' => 'employee',
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
        ];

        return $employeeData;
    }

    protected function _prepareFreetimeData($freetime)
    {
        $duration = $this->_calculateDuration($freetime->getStartAt(), $freetime->getEndAt());

        $freetimeData = [
            'type' => 'freetime',
            'freetime_id' => $freetime->id,
            'freetime_description' => $freetime->description,

            'date' => $freetime->date,
            'start_at' => $freetime->start_at,
            'end_at' => $freetime->end_at,
            'duration' => $duration,
        ];

        return $freetimeData;
    }

    protected function _prepareBookingData(Booking $booking)
    {
        $services = [];
        $bookingServices = $booking->bookingServices;
        foreach ($bookingServices as $bookingService) {
            $services[] = $bookingService->service->name;
        }
        $services = array_merge($services, $booking->getExtraServices());

        $consumerName = $booking->consumer->getNameAttribute();

        $duration = $this->_calculateDuration($booking->getStartAt(), $booking->getEndAt());

        $bookingData = [
            'type' => 'booking',
            'booking_id' => $booking->id,
            'booking_uuid' => $booking->uuid,
            'booking_services' => $services,
            'booking_notes' => $booking->notes,
            'consumer_name' => $consumerName,

            'date' => $booking->date,
            'start_at' => $booking->start_at,
            'end_at' => $booking->end_at,
            'duration' => $duration,
        ];

        return $bookingData;
    }

    protected function _prepareActiveData(Carbon $date, $hour, $minute)
    {
        // TODO: is it always 15 minutes?!
        $duration = 15;

        $activeStartAt = Carbon::createFromTime($hour, $minute, 0, Config::get('app.timezone'));

        $activeData = [
            'type' => 'active',
            'date' => $date->toDateString(),
            'start_at' => $activeStartAt->toTimeString(),
            'end_at' => $activeStartAt->addMinutes($duration)->toTimeString(),
            'duration' => $duration,
        ];

        return $activeData;
    }

    protected function _calculateDuration(Carbon $startAt, Carbon $endAt)
    {
        $diff = $endAt->diff($startAt);
        return ($diff->h * 60 + $diff->i);
    }
}
