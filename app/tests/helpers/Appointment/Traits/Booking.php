<?php
namespace Appointment\Traits;

use Config, Util;
use App\Appointment\Models\AsConsumer;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\User;
use Carbon\Carbon;

trait Booking
{
    protected function _book(\ApiTester $I, User $user, ServiceCategory $category, $startAt = null)
    {
        if (!Config::get('mail.pretend') || !Config::get('sms.pretend')) {
            $I->assertTrue(false, 'mail/sms pretend must be enabled');
        }

        $uuid = Util::uuid();
        $service = $category->services()->first();
        $employee = $service->employees()->first();
        $extraServices = $service->extraServices;
        $tomorrow = Carbon::today()->addDay();
        if (empty($startAt)) {
            // default to book at noon
            $startAt = '12:00';
        }

        $bookingService = BookingService::saveBookingService($uuid, $employee, $service, [
            'booking_date' => $tomorrow->toDateString(),
            'start_time' => $startAt,
            'modify_time' => rand(0, 3) * 15,
        ]);

        foreach ($extraServices as $extraService) {
            BookingExtraService::addExtraService($uuid, $employee, $bookingService, $extraService);
        }

        $consumer = AsConsumer::handleConsumer([
            'first_name' => 'Consumer First',
            'last_name' => 'Last ' . $user->id,
            'email' => 'consumer_' . $user->id . '@varaa.com',
            'phone' => '1234567890',
            'hash' => '',
        ], $this->user);

        return \App\Appointment\Models\Booking::saveBooking($uuid, $user, $consumer, []);
    }

    protected function _assertBooking(\ApiTester $I, \App\Appointment\Models\Booking $booking, array $bookingData)
    {
        $I->assertEquals($booking->id, $bookingData['booking_id'], "\$bookingData['booking_id']");
        $I->assertEquals($booking->uuid, $bookingData['booking_uuid'], "\$bookingData['booking_uuid']");

        $I->assertEquals($booking->bookingServices()->count(), count($bookingData['booking_services']), "count(\$bookingData['booking_services'])");
        $length = 0;
        foreach ($booking->bookingServices as $bookingService) {
            $bookingServiceDataFound = false;

            foreach ($bookingData['booking_services'] as $bookingServiceData) {
                if ($bookingServiceData['id'] == $bookingService->service->id) {
                    $I->assertEquals($bookingService->service->name, $bookingServiceData['name'], "\$bookingServiceData['name']");
                    $I->assertEquals($bookingService->service->description, $bookingServiceData['description'], "\$bookingServiceData['description']");
                    $I->assertEquals($bookingService->service->category->id, $bookingServiceData['category_id'], "\$bookingServiceData['category_id']");
                    $I->assertEquals($bookingService->modify_time, $bookingServiceData['modify_time'], "\$bookingServiceData['modify_time']");

                    if ($bookingService->service_time_id > 0) {
                        $I->assertEquals($bookingService->serviceTime->id, $bookingServiceData['service_time_id'], "\$bookingServiceData['service_time_id']");
                    } else {
                        $I->assertEquals('default', $bookingServiceData['service_time_id'], "\$bookingServiceData['service_time_id']");
                    }

                    $I->assertEquals($bookingService->service->price, $bookingServiceData['price'], "\$bookingServiceData['price']");

                    $I->assertEquals($bookingService->date, $bookingServiceData['date'], "\$bookingServiceData['date']");
                    $I->assertEquals($bookingService->start_at, $bookingServiceData['start_at'], "\$bookingServiceData['start_at']");
                    $I->assertEquals($bookingService->end_at, $bookingServiceData['end_at'], "\$bookingServiceData['end_at']");
                    $I->assertEquals($bookingService->calculateServiceLength(), $bookingServiceData['duration'], "\$bookingServiceData['duration']");

                    $bookingServiceDataFound = true;
                }
            }

            $I->assertTrue($bookingServiceDataFound, 'service: ' . $bookingService->service->name);
            $length += $bookingService->calculateServiceLength();
        }

        $I->assertEquals($booking->extraServices()->count(), count($bookingData['booking_extra_services']), "count(\$bookingData['booking_extra_services'])");
        foreach ($booking->extraServices as $bookingExtraService) {
            $bookingExtraServiceDataFound = false;

            foreach ($bookingData['booking_extra_services'] as $bookingExtraServiceData) {
                if ($bookingExtraServiceData['id'] == $bookingExtraService->extraService->id) {
                    $I->assertEquals($bookingExtraService->extraService->name, $bookingExtraServiceData['name'], "\$bookingExtraServiceData['name']");
                    $I->assertEquals($bookingExtraService->extraService->description, $bookingExtraServiceData['description'], "\$bookingExtraServiceData['description']");
                    $I->assertEquals($bookingExtraService->extraService->price, $bookingExtraServiceData['price'], "\$bookingExtraServiceData['price']");
                    $I->assertEquals($bookingExtraService->extraService->length, $bookingExtraServiceData['duration'], "\$bookingExtraServiceData['duration']");

                    $bookingExtraServiceDataFound = true;
                }
            }

            $I->assertTrue($bookingExtraServiceDataFound, 'extra service: ' . $bookingExtraService->extraService->name);
            $length += $bookingExtraService->extraService->length;
        }

        $I->assertEquals(\App\Appointment\Models\Booking::getStatusByValue($booking->status), $bookingData['booking_status'], "\$bookingData['booking_status']");
        $I->assertEquals($length, $bookingData['duration'], "\$bookingData['duration']");

        $I->assertEquals($booking->consumer->id, $bookingData['consumer']['id'], "\$bookingData['consumer']['id']");
        $I->assertEquals($booking->consumer->first_name, $bookingData['consumer']['first_name'], "\$bookingData['consumer']['first_name']");
        $I->assertEquals($booking->consumer->last_name, $bookingData['consumer']['last_name'], "\$bookingData['consumer']['last_name']");
        $I->assertEquals($booking->consumer->email, $bookingData['consumer']['email'], "\$bookingData['consumer']['email']");
        $I->assertEquals($booking->consumer->phone, $bookingData['consumer']['phone'], "\$bookingData['consumer']['phone']");
        $I->assertEquals($booking->consumer->address, $bookingData['consumer']['address'], "\$bookingData['consumer']['address']");
    }
}
