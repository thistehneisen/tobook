<?php namespace Test\Unit\Appointment\Models;

use App\Appointment\Models\AsConsumer;
use App\Appointment\Models\Consumer;
use App\Appointment\Models\Booking;
use App\Core\Models\User;
use \UnitTester;
use Test\Traits\Models;
use Carbon;

/**
 * @group as
 */
class EmployeeCest
{
    use Models;

    public function testGetWorkingTimesByDate(UnitTester $I)
    {
        if(empty($this->category)) {
            $this->initData();
            $this->initCustomTime();
        }
        $user = User::find(70);
        $I->amLoggedAs($user);
        $employee = $this->employee;
        $date = $this->getDate();
        $defaultEndTime = null;
        $workingTimes  = $employee->getWorkingTimesByDate($date, $defaultEndTime);

        $cooked = array(
            9  => array(0,15,30,45),
            10 => array(0,15,30,45),
            11 => array(0,15,30,45),
            12 => array(0,15,30,45),
            13 => array(0,15,30,45),
            14 => array(0,15,30,45),
            15 => array(0,15,30,45),
            16 => array(0,15,30,45),
            17 => array(0),
        );

        $I->assertEquals($workingTimes, $cooked);
    }

    public function testGetTimetable(UnitTester $I)
    {
        if(empty($this->service)) {
            $this->initData();
        }

        $this->initCustomTime();

        $user = User::find(70);
        $I->amLoggedAs($user);
        $employee = $this->employee;
        $date = $this->getDate();
        $I->assertEquals(count($this->extraServices), 2);

        $cooked = array(
            9  => array(0,15,30,45),
            10 => array(0,15,30,45),
            11 => array(0,15,30,45),
            12 => array(0,15,30,45),
            13 => array(0,15,30,45),
            14 => array(0,15,30,45),
            15 => array(0,15,30,45),
            16 => array(0,15,30,45),
            17 => array(0),
        );
        $defaultEndTime = null;
        $empCustomTime = $employee->employeeCustomTimes()
                    ->with('customTime')
                    ->where('date', $date)
                    ->first();
        $I->assertNotEmpty($empCustomTime);

        $workingTimes = $employee->getWorkingTimesByDate($date, $defaultEndTime);
        $I->assertEquals($workingTimes, $cooked);
        $x = $employee->isOverllapedWithFreetime($date->toDateString(), with(new \Carbon\Carbon('11:30')), with(new \Carbon\Carbon('13:00')));
        $I->assertFalse($x);
        $timetable = $employee->getTimeTable($this->service, $date, null, $this->extraServices, true, true);

        $startTime = $date->copy()->hour(11)->minute(30)->second(0);
        $endTime = $date->copy()->hour(13)->minute(0)->second(0);
        $isOverllapedWithFreetime = $employee->isOverllapedWithFreetime($date->toDateString(), $startTime, $endTime);
        $I->assertFalse($isOverllapedWithFreetime);

        $cookedTimetable = array(
            '09:00 - 10:15',
            '09:15 - 10:30',
            '09:30 - 10:45',
            '09:45 - 11:00',
            '10:00 - 11:15',
            '10:15 - 11:30',
            '10:30 - 11:45',
            '10:45 - 12:00',
            '11:00 - 12:15',
            '11:15 - 12:30',
            '11:30 - 12:45',
            '14:00 - 15:15',
            '14:15 - 15:30',
            '14:30 - 15:45',
            '14:45 - 16:00',
            '15:00 - 16:15',
            '15:15 - 16:30',
            '15:30 - 16:45'
        );
        $I->assertNotEmpty(array_keys($timetable));
        $I->assertEquals(array_keys($timetable), $cookedTimetable);
    }
}
