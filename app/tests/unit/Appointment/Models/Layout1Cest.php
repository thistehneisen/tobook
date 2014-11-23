<?php namespace Test\Appointment\Models;

use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeCustomTime;
use App\Appointment\Models\EmployeeFreetime;
use App\Appointment\Models\CustomTime;
use App\Core\Models\User;
use App\Appointment\Controllers\Embed\Layout3;
use \UnitTester;
use Carbon\Carbon;

/**
 * @group as
 */
class UnitLayout1Cest
{

    use \Appointment\Traits\Models;
    use \Appointment\Traits\Booking;

    public function testSlotsClassCustom(UnitTester $t)
    {
        $this->initData();
        $this->initCustomTime();
        $date = '2014-11-24';

        $hour = 8;
        $minute = 0;
        $slotClass = $this->employee->getSlotClass($date, $hour, $minute, 'frontend', $this->service);
        $t->assertContains('custom inactive', $slotClass);
        $t->assertEquals($slotClass, 'custom inactive');

        $hour = 13;
        $minute = 0;
        $slotClass = $this->employee->getSlotClass($date, $hour, $minute, 'frontend', $this->service);
        $t->assertContains('freetime', $slotClass);
        $t->assertNotContains('activefreetime', $slotClass);
        $t->assertNotContains('inactivefreetime', $slotClass);
        $t->assertEquals($slotClass, 'custom active freetime freetime-head');

        for($minute = 15; $minute <= 45; $minute+=15) {
            $slotClass = $this->employee->getSlotClass($date, $hour, $minute, 'frontend', $this->service);
            $t->assertContains('freetime', $slotClass);
            $t->assertNotContains('activefreetime', $slotClass);
            $t->assertNotContains('inactivefreetime', $slotClass);
            $t->assertEquals($slotClass, 'custom active freetime freetime-body');
        }

        $hour = 17;
        $minute = 15;
        $slotClass = $this->employee->getSlotClass($date, $hour, $minute, 'frontend', $this->service);
        $t->assertContains('custom inactive', $slotClass);
        $t->assertEquals($slotClass, 'custom inactive');

    }

    public function testSlotsClassDefault(UnitTester $t)
    {
        $this->initData();
        $date = '2014-11-24';

        $hour = 8;
        $minute = 0;
        $slotClass = $this->employee->getSlotClass($date, $hour, $minute, 'frontend', $this->service);
        $t->assertContains('active', $slotClass);
        $t->assertNotContains('custom', $slotClass);
        $t->assertEquals($slotClass, 'active');

        $hour = 13;

        for($minute = 0; $minute <= 45; $minute+=15) {
            $slotClass = $this->employee->getSlotClass($date, $hour, $minute, 'frontend', $this->service);
            $t->assertContains('active', $slotClass);
            $t->assertNotContains('inactive', $slotClass);
            $t->assertNotContains('custom', $slotClass);
            $t->assertEquals($slotClass, 'active');
        }

        $hour = 17;
        $minute = 15;
        $slotClass = $this->employee->getSlotClass($date, $hour, $minute, 'frontend', $this->service);
        $t->assertContains('active', $slotClass);
        $t->assertEquals($slotClass, 'active');
    }

    public function testSlotsClassBooking(UnitTester $t)
    {
        $this->initData();
        $this->initCustomTime();
        $date = new Carbon('2014-11-24');
        $booking = $this->_book($this->user, $this->category, $date, '14:00');
        $t->assertEquals($booking->date, '2014-11-24');

        $hour = 13;
        $minute = 0;
        $slotClass = $this->employee->getSlotClass($date->toDateString(), $hour, $minute, 'frontend', $this->service);
        $t->assertContains('freetime', $slotClass);
        $t->assertNotContains('activefreetime', $slotClass);
        $t->assertNotContains('inactivefreetime', $slotClass);
        $t->assertEquals($slotClass, 'custom active freetime freetime-head');

        for($minute = 15; $minute <= 45; $minute+=15) {
            $slotClass = $this->employee->getSlotClass($date->toDateString(), $hour, $minute, 'frontend', $this->service);
            $t->assertNotContains('activefreetime', $slotClass);
            $t->assertNotContains('inactivefreetime', $slotClass);
            $t->assertEquals($slotClass, 'custom active freetime freetime-body');
        }

        $hour = 14;
        $minute = 0;
        $slotClass = $this->employee->getSlotClass($date->toDateString(), $hour, $minute, 'frontend', $this->service);
        $t->assertNotContains('active', $slotClass);
        $t->assertNotContains('inactive', $slotClass);
        $t->assertContains('booked', $slotClass);
        $t->assertContains('confirmed', $slotClass);
        $expected = sprintf('booked confirmed slot-booked-head booking-id-%s', $booking->id);
        $t->assertEquals($slotClass, $expected);

        for($minute = 15; $minute <= 45; $minute+=15) {
            $slotClass = $this->employee->getSlotClass($date->toDateString(), $hour, $minute, 'frontend', $this->service);
            $t->assertNotContains('active', $slotClass);
            $t->assertNotContains('inactive', $slotClass);
            $t->assertEquals($slotClass, sprintf('booked confirmed slot-booked-body booking-id-%d', $booking->id));
        }

        $hour = 19;
        $minute = 0;
        $slotClass = $this->employee->getSlotClass($date->toDateString(), $hour, $minute, 'frontend', $this->service);
        $t->assertContains('custom', $slotClass);
        $t->assertEquals($slotClass, 'custom inactive');
    }

}
