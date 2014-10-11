<?php namespace App\API\v1_0\Appointment\Controllers;

use Config;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Booking;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

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
            'booking_status' => Booking::getStatusByValue($booking->status),
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

    protected function _prepareServiceData(Service $service)
    {
        $times = [[
            'service_time_id' => 'default',
            'length' => $service->length,
            'price' => $service->price,
        ]];
        foreach ($service->serviceTimes as $serviceTime) {
            $times[] = [
                'service_time_id' => $serviceTime->id,
                'length' => $serviceTime->length,
                'price' => $serviceTime->price,
            ];
        }

        $extras = [];
        foreach ($service->extraServices as $extraService) {
            $extras[] = [
                'extra_service_id' => $extraService->id,
                'name' => $extraService->name,
                'description' => $extraService->description,
                'length' => $extraService->length,
                'price' => $extraService->price,
            ];
        }

        $serviceData = [
            'type' => 'service',
            'service_id' => $service->id,
            'service_name' => $service->name,
            'service_description' => $service->description,
            'service_is_active' => $service->is_active,

            'category_id' => $service->category_id,
            'service_times' => $times,
            'extra_services' => $extras,
        ];

        return $serviceData;
    }

    protected function _prepareServiceCategoryData(ServiceCategory $category)
    {
        $categoryData = [
            'type' => 'service_category',
            'category_id' => $category->id,
            'category_name' => $category->name,
            'category_description' => $category->description,
        ];

        return $categoryData;
    }

    protected function _preparePagination(Paginator $pagination)
    {
        return [
            'total' => $pagination->getTotal(),
            'per_page' => $pagination->getPerPage(),
            'page' => $pagination->getCurrentPage(),
            'last_page' => $pagination->getLastPage(),
        ];
    }

    protected function _calculateDuration(Carbon $startAt, Carbon $endAt)
    {
        $diff = $endAt->diff($startAt);
        return ($diff->h * 60 + $diff->i);
    }
}
