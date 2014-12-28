<?php namespace Test\Appointment\Models\Reception;

use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Consumer;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Resource;
use App\Appointment\Models\ResourceService;
use App\Appointment\Models\Reception\FrontendReceptionist;
use App\Core\Models\User;
use Carbon\Carbon;
use \UnitTester;
use DB;
use Test\Traits\Models;

/**
 * @group as
 */
class FrontendReceptionCest
{
    use Models;

    protected $receptionist = null;

    private function basicSetup()
    {
        //Do we really need a common receptionist for all test?
    }

    public function testUpsertBookingService(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '14:00';

        $I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);

        $receptionist = new FrontendReceptionist();
        $receptionist->setBookingId(null)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default')
            ->setModifyTime(0);

        $bookingService = $receptionist->upsertBookingService();
    }

    public function testUpsertBooking(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '14:00';

        $I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);

        $receptionist = new FrontendReceptionist();
        $receptionist->setBookingId(null)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default')
            ->setModifyTime(0);

        $bookingService = $receptionist->upsertBookingService();

        $consumer = Consumer::handleConsumer([
            'first_name' => 'Consumer First',
            'last_name' => 'Last ' . $this->service->id,
            'email' => 'consumer_' . $this->service->id . '@varaa.com',
            'phone' => '1234567890',
            'hash' => '',
        ], $user);

        $receptionist = new FrontendReceptionist();
        $receptionist->setBookingId(null)
            ->setUUID($uuid)
            ->setUser($user)
            ->setNotes('notes')
            ->setIsRequestedEmployee(true)
            ->setConsumer($consumer)
            ->setClientIP('192.168.1.1')
            ->setSource('inhouse');

        $booking = $receptionist->upsertBooking();

        $I->assertNotEmpty($booking);
        $I->assertEquals($booking->total, 60);
        $I->assertEquals($booking->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($booking->endTime, $date->hour(15)->minute(0)->second(0));
        $I->assertEquals($booking->firstBookingService()->is_requested_employee, true);
    }

    public function testValidateWithRoom(UnitTester $I)
    {
        $this->initData(true, false, true);
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '09:00';

        $I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);

        $receptionist = new FrontendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default');

        $receptionist->validateWithRooms();

        $I->assertEquals($receptionist->getRoomId(), 1);

        $receptionist->upsertBookingService();

        $consumer = Consumer::handleConsumer([
            'first_name' => 'Consumer First',
            'last_name' => 'Last ' . $this->service->id,
            'email' => 'consumer_' . $this->service->id . '@varaa.com',
            'phone' => '1234567890',
            'hash' => '',
        ], $user);

        $receptionist = new FrontendReceptionist();
        $receptionist->setBookingId(null)
            ->setUUID($uuid)
            ->setUser($user)
            ->setStatus('confirmed')
            ->setNotes('')
            ->setIsRequestedEmployee(true)
            ->setConsumer($consumer)
            ->setClientIP('192.168.1.1')
            ->setSource('backend');

        $receptionist->setBookingService();

        $booking = $receptionist->upsertBooking();

        $receptionist = new FrontendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID(Booking::uuid())
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId(64)
            ->setServiceTimeId('default');

        $exception = array();
        try {
           $receptionist->validateWithRooms();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }

        $I->assertNotEmpty($exception[0]);
        $I->assertEquals($exception[0], trans('as.bookings.error.not_enough_rooms'));
    }
}
