<?php
namespace Test\Traits;

use App\API\v1_0\Appointment\Controllers\Consumer;
use Config, Util;
use App\Appointment\Models\AsConsumer;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\User;
use Carbon\Carbon;

trait Booking
{
    protected function _book(User $user, ServiceCategory $category, Carbon $date = null, $startAt = null, $employee = null)
    {
        if (!Config::get('mail.pretend') || !Config::get('sms.pretend')) {
            return null;
        }

        $uuid = Util::uuid();
        $service = $category->services()->first();

        $employee =  (empty($employee))
            ? $service->employees()->first()
            : $employee;

        $extraServices = $service->extraServices;

        if (empty($date)) {
            $date = $this->_getNextDate();
        }
        if (empty($startAt)) {
            // default to book at noon
            $startAt = '12:00';
        }

        $bookingService = BookingService::saveBookingService($uuid, $employee, $service, [
            'booking_date' => $date->toDateString(),
            'start_time' => $startAt,
            'modify_time' => rand(0, 3) * 15,
        ]);

        foreach ($extraServices as $extraService) {
            BookingExtraService::addExtraService($uuid, $employee, $bookingService, $extraService);
        }

        $consumer = AsConsumer::handleConsumer([
            'first_name' => 'Consumer First',
            'last_name' => 'Last ' . $category->id,
            'email' => 'consumer_' . $category->id . '@varaa.com',
            'phone' => '1234567890',
            'hash' => '',
        ], $user);

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

        $I->assertEquals('', $bookingData['booking_notes'], "\$bookingData['booking_notes']");
        $I->assertEquals(\App\Appointment\Models\Booking::getStatusByValue($booking->status), $bookingData['booking_status'], "\$bookingData['booking_status']");
        $I->assertEquals($length, $bookingData['duration'], "\$bookingData['duration']");

        $this->_assertConsumer($I, $booking->consumer, $bookingData['consumer']);
    }

    protected function _assertConsumer(\ApiTester $I, $consumer, array $consumerData) {
        $I->assertEquals($consumer->id, $consumerData['consumer_id'], "\$consumerData['id']");
        $I->assertEquals($consumer->first_name, $consumerData['consumer_first_name'], "\$consumerData['consumer_first_name']");
        $I->assertEquals($consumer->last_name, $consumerData['consumer_last_name'], "\$consumerData['consumer_last_name']");
        $I->assertEquals($consumer->email, $consumerData['consumer_email'], "\$consumerData['consumer_email']");
        $I->assertEquals($consumer->phone, $consumerData['consumer_phone'], "\$consumerData['consumer_phone']");
        $I->assertEquals($consumer->address, $consumerData['consumer_address'], "\$consumerData['consumer_address']");
        $I->assertEquals($consumer->city, $consumerData['consumer_city'], "\$consumerData['consumer_city']");
        $I->assertEquals($consumer->postcode, $consumerData['consumer_postcode'], "\$consumerData['consumer_postcode']");
        $I->assertEquals($consumer->country, $consumerData['consumer_country'], "\$consumerData['consumer_country']");
    }

    /**
     * @return Carbon
     */
    protected function _getNextDate()
    {
        $date = Carbon::today()->addDay();
        while ($date->dayOfWeek != 1) {
            $date->addDay();
        }

        return $date;
    }
}
