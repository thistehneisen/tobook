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
            'employee_id' => intval($employee->id),
            'employee_name' => strval($employee->name),
            'employee_email' => strval($employee->email),
            'employee_phone' => strval($employee->phone),
            'employee_is_active' => !empty($employee->is_active),
        ];

        return $employeeData;
    }

    protected function _prepareFreetimeData($freetime)
    {
        $duration = $this->_calculateDuration($freetime->getStartAt(), $freetime->getEndAt());

        $freetimeData = [
            'type' => 'freetime',
            'freetime_id' => intval($freetime->id),
            'freetime_description' => strval($freetime->description),

            'date' => strval($freetime->date),
            'start_at' => strval($freetime->start_at),
            'end_at' => strval($freetime->end_at),
            'duration' => intval($duration),
        ];

        return $freetimeData;
    }

    protected function _prepareBookingData(Booking $booking, $includeEmployee = true)
    {
        $services = [];
        foreach ($booking->bookingServices as $bookingService) {
            $serviceData = [
                'id' => intval($bookingService->service->id),
                'name' => strval($bookingService->service->name),
                'description' => strval($bookingService->service->description),
                'category_id' => intval($bookingService->service->category_id),
                'modify_time' => intval($bookingService->modify_time),
                'service_time_id' => (empty($bookingService->service_time_id) ? 'default' : strval($bookingService->serviceTime->id)),
                'price' => doubleval($bookingService->service->price),

                'date' => strval($bookingService->date),
                'start_at' => strval($bookingService->start_at),
                'end_at' => strval($bookingService->end_at),
                'duration' => intval($bookingService->calculateServiceLength()),
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
                'id' => intval($bookingExtraService->extraService->id),
                'name' => strval($bookingExtraService->extraService->name),
                'description' => strval($bookingExtraService->extraService->description),
                'price' => doubleval($bookingExtraService->extraService->price),
                'duration' => intval($bookingExtraService->extraService->length),
            ];
        }

        $duration = $this->_calculateDuration($booking->getStartAt(), $booking->getEndAt());

        $bookingData = [
            'type' => 'booking',
            'booking_id' => intval($booking->id),
            'booking_uuid' => strval($booking->uuid),
            'booking_notes' => !empty($booking->notes) ? strval($booking->notes) : '',
            'booking_status' => Booking::getStatusByValue($booking->status),

            'consumer' => $this->_prepareConsumerData($booking->consumer),
            'booking_services' => $services,
            'booking_extra_services' => $extraServices,

            'date' => strval($booking->date),
            'start_at' => strval($booking->start_at),
            'end_at' => strval($booking->end_at),
            'duration' => intval($duration),
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
            'date' => strval($date->toDateString()),
            'start_at' => strval($activeStartAt->toTimeString()),
            'end_at' => strval($activeStartAt->addMinutes($duration)->toTimeString()),
            'duration' => intval($duration),
        ];

        return $activeData;
    }

    protected function _prepareServiceData(Service $service)
    {
        $times = [[
            'id' => 'default',
            'length' => intval($service->length),
            'price' => doubleval($service->price),
        ]];
        foreach ($service->serviceTimes as $serviceTime) {
            $times[] = [
                'id' => strval($serviceTime->id),
                'length' => intval($serviceTime->length),
                'price' => doubleval($serviceTime->price),
            ];
        }

        $extras = [];
        foreach ($service->extraServices as $extraService) {
            $extras[] = [
                'id' => intval($extraService->id),
                'name' => strval($extraService->name),
                'description' => strval($extraService->description),
                'length' => intval($extraService->length),
                'price' => doubleval($extraService->price),
            ];
        }

        $serviceData = [
            'type' => 'service',
            'service_id' => intval($service->id),
            'service_name' => strval($service->name),
            'service_description' => strval($service->description),
            'service_is_active' => !empty($service->is_active),

            'category_id' => intval($service->category_id),
            'service_times' => $times,
            'extra_services' => $extras,
        ];

        return $serviceData;
    }

    protected function _prepareServiceCategoryData(ServiceCategory $category)
    {
        $categoryData = [
            'type' => 'service_category',
            'category_id' => intval($category->id),
            'category_name' => strval($category->name),
            'category_description' => strval($category->description),
        ];

        return $categoryData;
    }

    protected function _prepareConsumerData($consumer) {
        $consumerData = [
            'type' => 'consumer',
            'consumer_id' => intval($consumer->id),
            'consumer_first_name' => strval($consumer->first_name),
            'consumer_last_name' => strval($consumer->last_name),
            'consumer_email' => strval($consumer->email),
            'consumer_phone' => strval($consumer->phone),
            'consumer_address' => strval($consumer->address),
            'consumer_city' => strval($consumer->city),
            'consumer_postcode' => strval($consumer->postcode),
            'consumer_country' => strval($consumer->country),
        ];

        return $consumerData;
    }

    protected function _preparePagination($pagination)
    {
        if ($pagination instanceof Paginator) {
            return [
                'total' => intval($pagination->getTotal()),
                'per_page' => intval($pagination->getPerPage()),
                'page' => intval($pagination->getCurrentPage()),
                'last_page' => intval($pagination->getLastPage()),
            ];
        } else {
            return [
                'total' => count($pagination),
                'per_page' => count($pagination),
                'page' => 1,
                'last_page' => 1,
            ];
        }
    }

    protected function _calculateDuration(Carbon $startAt, Carbon $endAt)
    {
        $diff = $endAt->diff($startAt);
        return ($diff->h * 60 + $diff->i);
    }
}
