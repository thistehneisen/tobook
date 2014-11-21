<?php namespace Test\Appointment\Models;

use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\User;
use \UnitTester;

/**
 * @group as
 */
class BookingExtraServiceCest
{
    use \Test\Traits\Booking;

    public function testAddExtraServiceSuccess(UnitTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $booking = $this->_book($user, $category);
        $I->assertNotEmpty($booking, 'booking');

        $uuid = $booking->uuid;
        $employee = $booking->employee;
        $bookingService = $booking->bookingServices()->first();

        $extraService = new ExtraService([
            'name' => 'New Extra',
            'price' => 10,
            'length' => 15,
        ]);
        $extraService->user()->associate($user);
        $extraService->saveOrFail();

        $bookingExtraService = BookingExtraService::addExtraService($uuid, $employee, $bookingService, $extraService);
        $I->assertNotEmpty($bookingExtraService, 'booking extra service');
        $I->assertEquals($extraService->id, $bookingExtraService->extra_service_id, 'extra service');
    }

    public function testAddExtraServiceOverlap(UnitTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $date = $this->_getNextDate();
        $booking1 = $this->_book($user, $category, $date);
        $I->assertNotEmpty($booking1, 'booking');

        $booking2 = $this->_book($user, $category, $date, $booking1->end_at);
        $I->assertNotEmpty($booking2, 'booking2');
        $I->assertNotEquals($booking1->id, $booking2->id, 'booking2 <> booking');

        $uuid = $booking1->uuid;
        $employee = $booking1->employee;
        $bookingService = $booking1->bookingServices()->first();

        $extraService = new ExtraService([
            'name' => 'New Extra',
            'price' => 10,
            'length' => 15,
        ]);
        $extraService->user()->associate($user);
        $extraService->saveOrFail();

        $e = null;
        try {
            BookingExtraService::addExtraService($uuid, $employee, $bookingService, $extraService);
        } catch (\Watson\Validating\ValidationException $wvve) {
            $e = $wvve;
        }
        $I->assertNotEmpty($e, 'exception');
    }
}
