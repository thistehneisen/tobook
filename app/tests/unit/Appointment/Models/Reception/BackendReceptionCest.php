<?php namespace Test\Appointment\Models\Reception;

use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Consumer;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Resource;
use App\Appointment\Models\ResourceService;
use App\Appointment\Models\Reception\BackendReceptionist;
use App\Core\Models\User;
use Carbon\Carbon;
use \UnitTester;
use DB;
use Test\Traits\Models;

/**
 * @group as
 */
class BackendReceptionCest
{
    use Models;

    protected $receptionist = null;

    private function basicSetup()
    {
        //Do we really need a common receptionist for all test?
    }

    public function testBookingServicePrice(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '10:00';

        $I->amLoggedAs($user);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default');

        $I->assertEquals($service->price, $receptionist->computeTotalPrice());
    }

    public function testValidateData(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '10:00';

        $I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);
        $I->assertNotEmpty($service);
        $I->assertNotEmpty($employee);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default')
            ->setModifyTime(-60)
            ->setIsRequestedEmployee(false);

        $exception = array();

        try {
           $receptionist->validateBookingTotal();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }

        $I->assertNotEmpty($exception[0]);
        $I->assertEquals($exception[0], trans('as.bookings.error.empty_total_time'));

        $receptionist->setModifyTime(0)->setStartTime('23:30');

        try {
           $receptionist->validateBookingTime();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }
        $I->assertNotEmpty($exception[1]);
        $I->assertEquals($exception[1], trans('as.bookings.error.exceed_current_day'));
    }

    public function testValidateBooking(UnitTester $I)
    {

        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '13:00';

        $I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default');

        $exception = array();

        try {
           $receptionist->validateWithEmployeeFreetime();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }

        $I->assertNotEmpty($exception[0]);
        $I->assertEquals($exception[0], trans('as.bookings.error.overllapped_with_freetime'));
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

        $receptionist = new BackendReceptionist();
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

        $receptionist = new BackendReceptionist();
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

        $receptionist = new BackendReceptionist();
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

    public function testResponseData(UnitTester $I)
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

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default');

        $response = $receptionist->getResponseData();
        $plustime = $employee->getPlustime($service->id);

        $cooked = [
            'datetime'      => $date->hour(14)->toDateTimeString(),
            'price'         => $service->price,
            'service_name'  => $service->name,
            'modify_time'   => 0,
            'plustime'      => intval($plustime),
            'employee_name' => $employee->name,
            'uuid'          => $uuid
        ];

        $I->assertEquals($response, $cooked);
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

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default');

        $bookingService = $receptionist->upsertBookingService();
        $I->assertEquals($bookingService->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($bookingService->endTime, $date->hour(15)->minute(0)->second(0));

        $consumer = Consumer::handleConsumer([
            'first_name' => 'Consumer First',
            'last_name' => 'Last ' . $this->service->id,
            'email' => 'consumer_' . $this->service->id . '@varaa.com',
            'phone' => '1234567890',
            'hash' => '',
        ], $user);

        $receptionist = new BackendReceptionist();
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

        $bookingService = $receptionist->getBookingService();
        $I->assertEquals($bookingService->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($bookingService->endTime, $date->hour(15)->minute(0)->second(0));

        $booking = $receptionist->upsertBooking();
        $I->assertNotEmpty($booking);
        $I->assertEquals($booking->total, 60);
        $I->assertEquals($booking->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($booking->endTime, $date->hour(15)->minute(0)->second(0));
    }
}
