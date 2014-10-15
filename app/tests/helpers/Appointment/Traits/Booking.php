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
    protected function _book(\ApiTester $I, User $user, ServiceCategory $category)
    {
        if (!Config::get('mail.pretend') || !Config::get('sms.pretend')) {
            $I->assertTrue(false, 'mail/sms pretend must be enabled');
        }

        $uuid = Util::uuid();
        $service = $category->services()->first();
        $employee = $service->employees()->first();
        $extraServices = $service->extraServices;
        $tomorrow = Carbon::today()->addDay();
        $startAt = '12:00';

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
        foreach ($booking->bookingServices as $bookingService) {
            $bookingServiceDataFound = false;

            foreach ($bookingData['booking_services'] as $bookingServiceData) {
                if ($bookingServiceData['id'] == $bookingService->service->id) {
                    $I->assertEquals($bookingService->service->name, $bookingServiceData['name'], "\$bookingServiceData['name']");
                    $I->assertEquals($bookingService->service->description, $bookingServiceData['description'], "\$bookingServiceData['description']");
                    $I->assertEquals($bookingService->modify_time, $bookingServiceData['modify_time'], "\$bookingServiceData['modify_time']");

                    $bookingServiceDataFound = true;
                }
            }

            $I->assertTrue($bookingServiceDataFound, 'service: ' . $bookingService->service->name);
        }

        $I->assertEquals($booking->extraServices()->count(), count($bookingData['booking_extra_services']), "count(\$bookingData['booking_extra_services'])");
        foreach ($booking->extraServices as $bookingExtraService) {
            $bookingExtraServiceDataFound = false;

            foreach ($bookingData['booking_extra_services'] as $bookingExtraServiceData) {
                if ($bookingExtraServiceData['id'] == $bookingExtraService->extraService->id) {
                    $I->assertEquals($bookingExtraService->extraService->name, $bookingExtraServiceData['name'], "\$bookingExtraServiceData['name']");
                    $I->assertEquals($bookingExtraService->extraService->description, $bookingExtraServiceData['description'], "\$bookingExtraServiceData['description']");

                    $bookingExtraServiceDataFound = true;
                }
            }

            $I->assertTrue($bookingExtraServiceDataFound, 'extra service: ' . $bookingExtraService->extraService->name);
        }

        $I->assertEquals(\App\Appointment\Models\Booking::getStatusByValue($booking->status), $bookingData['booking_status'], "\$bookingData['booking_status']");

        $I->assertEquals($booking->consumer->id, $bookingData['consumer']['id'], "\$bookingData['consumer']['id']");
        $I->assertEquals($booking->consumer->name, $bookingData['consumer']['name'], "\$bookingData['consumer']['name']");
    }
}
