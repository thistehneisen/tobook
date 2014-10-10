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

        $serviceCount = 0;
        $bookingServices = $booking->bookingServices;
        foreach ($bookingServices as $bookingService) {
            $I->assertTrue(in_array($bookingService->service->name, $bookingData['booking_services']), 'service: ' . $bookingService->service->name);
            $serviceCount++;
        }

        $extraSevices = $booking->extraServices;
        foreach ($extraSevices as $extraSevice) {
            $I->assertTrue(in_array($extraSevice->extraService->name, $bookingData['booking_services']), 'extra: ' . $extraSevice->extraService->name);
            $serviceCount++;
        }

        $I->assertEquals($serviceCount, count($bookingData['booking_services']), "count(\$bookingData['booking_services'])");

        $I->assertEquals(\App\Appointment\Models\Booking::getStatusByValue($booking->status), $bookingData['booking_status'], "\$bookingData['booking_status']");

        $I->assertEquals($booking->consumer->name, $bookingData['consumer_name'], "\$bookingData['consumer_name']");
    }
}
