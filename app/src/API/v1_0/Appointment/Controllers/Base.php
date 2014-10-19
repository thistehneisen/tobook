<?php namespace App\API\v1_0\Appointment\Controllers;

use App\Appointment\Models\Consumer;
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

    protected function _prepareBookingData(Booking $booking, $includeEmployee = true)
    {
        $services = [];
        foreach ($booking->bookingServices as $bookingService) {
            $serviceData = [
                'id' => $bookingService->service->id,
                'name' => $bookingService->service->name,
                'description' => $bookingService->service->description,
                'category_id' => $bookingService->service->category_id,
                'modify_time' => $bookingService->modify_time,
                'service_time_id' => (empty($bookingService->service_time_id) ? 'default' : $bookingService->serviceTime->id),
                'price' => $bookingService->service->price,

                'date' => $bookingService->date,
                'start_at' => $bookingService->start_at,
                'end_at' => $bookingService->end_at,
                'duration' => $bookingService->calculateServiceLength(),
            ];

            // this is weird, end_at is 00:00:00 for some records
            // we are going to fix it in the API
            if ($serviceData['end_at'] === '00:00:00') {
                $serviceData['end_at'] = $bookingService->getStartAt()->addMinutes($serviceData['duration'])->toTimeString();
            }

            $services[] = $serviceData;
        }

        $extraServices = [];
        foreach ($booking->extraServices as $bookingExtraService) {
            $extraServices[] = [
                'id' => $bookingExtraService->extraService->id,
                'name' => $bookingExtraService->extraService->name,
                'description' => $bookingExtraService->extraService->description,
                'price' => $bookingExtraService->extraService->price,
                'duration' => $bookingExtraService->extraService->length,
            ];
        }

        $duration = $this->_calculateDuration($booking->getStartAt(), $booking->getEndAt());

        $bookingData = [
            'type' => 'booking',
            'booking_id' => $booking->id,
            'booking_uuid' => $booking->uuid,
            'booking_notes' => $booking->notes,
            'booking_status' => Booking::getStatusByValue($booking->status),

            'consumer' => $this->_prepareConsumerData($booking->consumer),
            'booking_services' => $services,
            'booking_extra_services' => $extraServices,

            'date' => $booking->date,
            'start_at' => $booking->start_at,
            'end_at' => $booking->end_at,
            'duration' => $duration,
        ];

        if ($includeEmployee) {
            $bookingData['employee'] = $this->_prepareEmployeeData($booking->employee);
        }

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
            'id' => 'default',
            'length' => $service->length,
            'price' => $service->price,
        ]];
        foreach ($service->serviceTimes as $serviceTime) {
            $times[] = [
                'id' => $serviceTime->id,
                'length' => $serviceTime->length,
                'price' => $serviceTime->price,
            ];
        }

        $extras = [];
        foreach ($service->extraServices as $extraService) {
            $extras[] = [
                'id' => $extraService->id,
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

    protected function _prepareConsumerData($consumer) {
        $consumerData = [
            'type' => 'consumer',
            'consumer_id' => $consumer->id,
            'consumer_first_name' => $consumer->first_name,
            'consumer_last_name' => $consumer->last_name,
            'consumer_email' => $consumer->email,
            'consumer_phone' => $consumer->phone,
            'consumer_address' => $consumer->address,
        ];

        return $consumerData;
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