<?php
namespace Appointment\Schedule;

use \ApiTester;
use Appointment\Traits\Booking;
use Appointment\Traits\Models;
use Carbon\Carbon;

class BookingCest
{
    use Booking;
    use Models;

    protected $endpoint = '/api/v1.0/as/bookings';
    protected $category = null;

    public function _before(ApiTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();
        $this->_createEmployee(2);

        $categories = $this->_createCategoryServiceAndExtra(1, 1, 2);
        $this->category = reset($categories);

        // do not use amHttpAuthenticated because route filters are disabled by default
        // and amLoggedAs is just faster!
        $I->amLoggedAs($this->user);
    }

    public function testStore(ApiTester $I)
    {
        $employee = $this->employees[0];
        $service = $this->category->services->first();
        $extraServices = $service->extraServices;
        $extraServiceIds = [];
        foreach ($extraServices as $extraService) {
            $extraServiceIds[] = $extraService->id;
        }

        $consumerFirstName = 'Consumer First';
        $consumerLastName = 'Last ' . $this->user->id;
        $consumerEmail = 'consumer_' . $this->user->id . '@varaa.com';
        $consumerPhone = '1234567890';
        $consumerAddress = 'address';

        $dayObj = Carbon::today()->addDay();
        $startAt = '12:00:00';

        $I->sendPOST($this->endpoint, [
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'extra_services' => $extraServiceIds,

            // consumer
            'first_name' => $consumerFirstName,
            'last_name' => $consumerLastName,
            'email' => $consumerEmail,
            'phone' => $consumerPhone,
            'address' => $consumerAddress,
            // 'hash' => '',

            // booking service
            'booking_date' => $dayObj->toDateString(),
            // 'modify_times' => '',
            // 'service_time' => '',
            'start_time' => substr($startAt, 0, 5),

            // booking
            // 'notes' => '',
            // 'status' => '',
            // 'ip' => '',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $bookingData = $I->grabDataFromJsonResponse('booking');
        $booking = \App\Appointment\Models\Booking::find($bookingData['booking_id']);

        $I->assertEquals($employee->id, $booking->employee_id, 'employee_id');

        $I->assertEquals($service->id, $booking->bookingServices()->first()->service_id, 'service: ' . $service->name);
        foreach ($extraServices as $extraService) {
            $extraServiceFound = false;
            foreach ($booking->extraServices as $bookingExtraService) {
                if ($bookingExtraService->extra_service_id == $extraService->id) {
                    $extraServiceFound = true;
                    break;
                }
            }
            $I->assertTrue($extraServiceFound, 'extra: ' . $extraService->name);
        }

        $I->assertEquals($consumerFirstName, $booking->consumer->first_name, 'consumer.first_name');
        $I->assertEquals($consumerLastName, $booking->consumer->last_name, 'consumer.last_name');
        $I->assertEquals($consumerEmail, $booking->consumer->email, 'consumer.email');
        $I->assertEquals($consumerPhone, $booking->consumer->phone, 'consumer.phone');
        $I->assertEquals($consumerAddress, $booking->consumer->address, 'consumer.address');

        $I->assertEquals($dayObj->toDateString(), $booking->date, 'date');
        $I->assertEquals($startAt, $booking->start_at, 'start_at');
        $I->assertEquals(\App\Appointment\Models\Booking::STATUS_CONFIRM, $booking->status, 'status');
    }

    public function testShow(ApiTester $I)
    {
        $booking = $this->_book($I, $this->user, $this->category);

        $I->sendGET($this->endpoint . '/' . $booking->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['booking_id' => $booking->id]);

        $bookingData = $I->grabDataFromJsonResponse('booking');
        $this->_assertBooking($I, $booking, $bookingData);
    }

    public function testUpdate(ApiTester $I)
    {
        $booking = $this->_book($I, $this->user, $this->category);

        $employee = $this->employees[1];
        $service = $this->category->services->first();
        $extraService = $service->extraServices->first();

        $consumerFirstName = $booking->consumer->first_name;
        $consumerLastName = $booking->consumer->last_name;
        $consumerEmail = $booking->consumer->email;
        $consumerPhone = '0987654321';
        $consumerAddress = 'new address';

        $dayObj = Carbon::today()->addDay();
        $startAt = '13:00:00';

        $I->sendPUT($this->endpoint . '/' . $booking->id, [
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'extra_services' => [$extraService->id],

            // consumer
            'first_name' => $consumerFirstName,
            'last_name' => $consumerLastName,
            'email' => $consumerEmail,
            'phone' => $consumerPhone,
            'address' => $consumerAddress,
            // 'hash' => '',

            // booking service
            'booking_date' => $dayObj->toDateString(),
            // 'modify_times' => '',
            // 'service_time' => '',
            'start_time' => substr($startAt, 0, 5),

            // booking
            // 'notes' => '',
            // 'status' => '',
            // 'ip' => '',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $bookingData = $I->grabDataFromJsonResponse('booking');
        $booking = \App\Appointment\Models\Booking::find($bookingData['booking_id']);

        $I->assertEquals($employee->id, $booking->employee_id, 'employee_id');

        $extraServiceFound = false;
        foreach ($booking->extraServices as $bookingExtraService) {
            if ($bookingExtraService->extra_service_id == $extraService->id) {
                $extraServiceFound = true;
                break;
            }
        }
        $I->assertTrue($extraServiceFound, 'extra: ' . $extraService->name);

        $I->assertEquals($consumerPhone, $booking->consumer->phone, 'consumer.phone');
        $I->assertEquals($consumerAddress, $booking->consumer->address, 'consumer.address');

        $I->assertEquals($startAt, $booking->start_at, 'start_at');
    }

    public function testDestroy(ApiTester $I)
    {
        $booking = $this->_book($I, $this->user, $this->category);

        $I->sendDELETE($this->endpoint . '/' . $booking->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => false]);

        $booking = \App\Appointment\Models\Booking::find($booking->id);
        $I->assertTrue(empty($booking), 'empty($booking)');
    }
}
