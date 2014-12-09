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

        $selectedService = $receptionist->setSelectedService();

        $I->assertEquals($service->price, $receptionist->computeTotalPrice());
    }
}
