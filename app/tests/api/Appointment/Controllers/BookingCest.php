<?php namespace Test\Api\Appointment\Controllers;

use \ApiTester;
use App\Appointment\Models\AsConsumer;
use Test\Traits\Booking;
use Test\Traits\Models;
use Carbon\Carbon;

/**
 * @group as
 */
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
        $consumer = AsConsumer::handleConsumer([
            'first_name' => $consumerFirstName,
            'last_name' => $consumerLastName,
            'email' => $consumerEmail,
            'phone' => $consumerPhone,
            'address' => $consumerAddress,
            'hash' => '',
        ], $this->user);

        $dayObj = Carbon::today()->addDay();
        $startAt = '12:00:00';

        $I->sendPOST($this->endpoint, [
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'extra_services' => $extraServiceIds,
            'consumer_id' => $consumer->id,

            // booking service
            'booking_date' => $dayObj->toDateString(),
            // 'modify_times' => '',
            // 'service_time' => '',
            'start_time' => substr($startAt, 0, 5),

            // booking
            // 'booking_notes' => '',
            // 'booking_status' => '',
            // 'ip' => '',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $bookingData = $I->grabDataFromJsonResponse('data');
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
        $I->assertEmpty($booking->notes, 'notes');
        $I->assertEquals(\App\Appointment\Models\Booking::STATUS_CONFIRM, $booking->status, 'status');
    }

    public function testShow(ApiTester $I)
    {
        $booking = $this->_book($this->user, $this->category);

        $I->sendGET($this->endpoint . '/' . $booking->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['booking_id' => $booking->id]);

        $bookingData = $I->grabDataFromJsonResponse('data');
        $this->_assertBooking($I, $booking, $bookingData);
    }

    public function testUpdate(ApiTester $I)
    {
        $booking = $this->_book($this->user, $this->category);

        $employee = $this->employees[1];
        $service = $this->category->services->first();
        $extraService = $service->extraServices->first();

        $consumerFirstName = $booking->consumer->first_name;
        $consumerLastName = $booking->consumer->last_name;
        $consumerEmail = $booking->consumer->email;
        $consumerPhone = '0987654321';
        $consumerAddress = 'new address';
        $consumer = AsConsumer::handleConsumer([
            'first_name' => $consumerFirstName,
            'last_name' => $consumerLastName,
            'email' => $consumerEmail,
            'phone' => $consumerPhone,
            'address' => $consumerAddress,
            'hash' => '',
        ], $this->user);

        $dayObj = Carbon::today()->addDay();
        $startAt = '13:00:00';

        $notes = 'Notes';
        $status = \App\Appointment\Models\Booking::STATUS_ARRIVED;

        $I->sendPUT($this->endpoint . '/' . $booking->id, [
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'extra_services' => [$extraService->id],
            'consumer_id' => $consumer->id,

            // booking service
            'booking_date' => $dayObj->toDateString(),
            // 'modify_times' => '',
            // 'service_time' => '',
            'start_time' => substr($startAt, 0, 5),

            // booking
            'booking_notes' => $notes,
            'booking_status' => \App\Appointment\Models\Booking::getStatusByValue($status),
            // 'ip' => '',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $bookingData = $I->grabDataFromJsonResponse('data');
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
        $I->assertEquals($notes, $booking->notes, 'notes');
        $I->assertEquals($status, $booking->status, 'status');
    }

    public function testDestroy(ApiTester $I)
    {
        $booking = $this->_book($this->user, $this->category);

        $I->sendDELETE($this->endpoint . '/' . $booking->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => false]);

        $booking = \App\Appointment\Models\Booking::find($booking->id);
        $I->assertTrue(empty($booking), 'empty($booking)');
    }

    public function testPutStatus(ApiTester $I)
    {
        $booking = $this->_book($this->user, $this->category);

        $statusPaid = \App\Appointment\Models\Booking::STATUS_PAID;
        $statusPaidApi = \App\Appointment\Models\Booking::getStatusByValue($statusPaid);
        $I->sendPUT($this->endpoint . '/' . $booking->id . '/status', [
            'booking_status' => $statusPaidApi,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => false]);

        $booking = \App\Appointment\Models\Booking::find($booking->id);
        $I->assertEquals($statusPaid, $booking->status, '$booking->status');
    }

    public function testPutModifyTime(ApiTester $I)
    {
        $booking = $this->_book($this->user, $this->category);
        $modifyTime = 120;

        $I->sendPUT($this->endpoint . '/' . $booking->id . '/modify_time', [
            'modify_time' => $modifyTime,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => false]);

        $booking = \App\Appointment\Models\Booking::find($booking->id);
        $I->assertEquals($modifyTime, $booking->modify_time, '$booking->modify_time');
    }

    public function testPutModifyTimeWithConflict(ApiTester $I)
    {
        $booking = $this->_book($this->user, $this->category, $this->_getNextDate(), '08:00');
        $booking2 = $this->_book($this->user, $this->category, $this->_getNextDate(), '14:00');
        $modifyTime = $booking->modify_time + $booking2->getStartAt()->diffInMinutes($booking->getEndAt()) + 15;

        $I->sendPUT($this->endpoint . '/' . $booking->id . '/modify_time', [
            'modify_time' => $modifyTime,
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => true]);

        $bookingAgain = \App\Appointment\Models\Booking::find($booking->id);
        $I->assertEquals($booking->modify_time, $bookingAgain->modify_time, '$bookingAgain->modify_time');
        $I->assertEquals($booking->end_at, $bookingAgain->end_at, '$bookingAgain->end_at');
    }

    public function testPutScheduleSameEmployee(ApiTester $I)
    {
        $booking = $this->_book($this->user, $this->category);
        $duration = $booking->getEndAt()->diffInMinutes($booking->getStartAt());

        $date = Carbon::today();
        do {
            // find the next Monday
            $date->addDay();
        } while ($date->format('N') == 1);
        $starAt = '10:00:00';
        $employeeId = $booking->employee->id;

        $I->sendPUT($this->endpoint . '/' . $booking->id . '/schedule', [
            'booking_date' => $date->toDateSTring(),
            'start_time' => $starAt,
            'employee_id' => $employeeId,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => false]);

        $booking = \App\Appointment\Models\Booking::find($booking->id);
        $I->assertEquals($date->toDateString(), $booking->date, '$booking->date');
        $I->assertEquals($starAt, $booking->start_at, '$booking->start_at');
        $I->assertEquals($duration, $booking->getEndAt()->diffInMinutes($booking->getStartAt()), '$duration');
        $I->assertEquals($employeeId, $booking->employee_id, '$booking->employee_id');
    }

    public function testPutScheduleDifferentEmployee(ApiTester $I)
    {
        $booking = $this->_book($this->user, $this->category);
        $duration = $booking->getEndAt()->diffInMinutes($booking->getStartAt());

        $date = Carbon::today();
        do {
            // find the next Monday
            $date->addDay();
        } while ($date->format('N') == 1);
        $starAt = '10:00:00';
        $employeeId = $this->employees[1]->id;
        $I->assertNotEquals($booking->employee->id, $employeeId);

        $I->sendPUT($this->endpoint . '/' . $booking->id . '/schedule', [
            'booking_date' => $date->toDateSTring(),
            'start_time' => $starAt,
            'employee_id' => $employeeId,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => false]);

        $booking = \App\Appointment\Models\Booking::find($booking->id);
        $I->assertEquals($date->toDateString(), $booking->date, '$booking->date');
        $I->assertEquals($starAt, $booking->start_at, '$booking->start_at');
        $I->assertEquals($duration, $booking->getEndAt()->diffInMinutes($booking->getStartAt()), '$duration');
        $I->assertEquals($employeeId, $booking->employee_id, '$booking->employee_id');
    }

    public function testPutScheduleWithConflict(ApiTester $I)
    {
        $booking = $this->_book($this->user, $this->category, $this->_getNextDate(), '08:00');
        $duration = $booking->getEndAt()->diffInMinutes($booking->getStartAt());
        $booking2 = $this->_book($this->user, $this->category, $this->_getNextDate(), '14:00');

        $I->sendPUT($this->endpoint . '/' . $booking->id . '/schedule', [
            'booking_date' => $booking->date,
            'start_time' => $booking2->start_at,
            'employee_id' => $booking->employee->id,
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => true]);

        $bookingAgain = \App\Appointment\Models\Booking::find($booking->id);
        $I->assertEquals($booking->date, $bookingAgain->date, '$bookingAgain->date');
        $I->assertEquals($booking->start_at, $bookingAgain->start_at, '$bookingAgain->start_at');
        $I->assertEquals($duration, $bookingAgain->getEndAt()->diffInMinutes($bookingAgain->getStartAt()), '$duration');
        $I->assertEquals($booking->employee_id, $bookingAgain->employee_id, '$bookingAgain->employee_id');
    }
}
