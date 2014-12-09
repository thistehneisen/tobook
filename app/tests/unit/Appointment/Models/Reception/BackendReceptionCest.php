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
use App\Appointment\Models\AsConsumer;
use App\Appointment\Models\Reception\BackendReceptionist;
use App\Core\Models\User;
use Carbon\Carbon;
use \UnitTester;
use DB, Util;
use Test\Traits\Models;

/**
 * @group as
 */
class BackednReceptionCest
{
    use Models;

    protected $receptionist = null;

    private function basicSetup()
    {
        //Do we really need a common receptionist for all test?
    }

    public function testBookingServicePrice(UnitTester $I)
    {
        if(empty($this->service)) {
            $this->initData();
            $this->initCustomTime();
        }

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = \Util::uuid();

        $date      = Carbon::today();
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
        if(empty($this->service)) {
            $this->initData();
            $this->initCustomTime();
        }

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = \Util::uuid();

        $date      = Carbon::today();
        $startTime = '10:00';

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
            ->setServiceTimeId('default')
            ->setModifyTime(-60);

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
           $receptionist->validateBookingEndTime();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }
        $I->assertNotEmpty($exception[1]);
        $I->assertEquals($exception[1], trans('as.bookings.error.exceed_current_day'));
    }

    public function testValidateBooking(UnitTester $I)
    {
        if(empty($this->service)) {
            $this->initData();
            $this->initCustomTime();
        }

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = \Util::uuid();

        $date      = Carbon::today();
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
}
