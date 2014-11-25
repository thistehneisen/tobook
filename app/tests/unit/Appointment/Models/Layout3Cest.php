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
class UnitLayout3Cest
{

    use \Test\Traits\Models;

    public function testTimetableOfSingleCustom(UnitTester $t)
    {
        $this->initData();
        $this->initCustomTime();

        $layout3 = new Layout3;
        $date = new Carbon('2014-11-24 00:00:00');
        $timeTable = $layout3->getTimetableOfSingle($this->employee, $this->service, $date);
        // Expected result with custome time and freetime
        // Custom time from 9:00 to 17:00 and have freetime from 13:00 to 14:00
        $expectedResult = [
            '09:00',
            '09:15',
            '09:30',
            '09:45',
            '10:00',
            '10:15',
            '10:30',
            '10:45',
            '11:00',
            '11:15',
            '11:30',
            '11:45',
            '12:00',
            '14:00',
            '14:15',
            '14:30',
            '14:45',
            '15:00',
            '15:15',
            '15:30',
            '15:45',
            '16:00',
        ];
        $t->assertEquals(array_diff(array_keys($timeTable), $expectedResult), []);
    }

    public function testTimetableOfSingleDefault(UnitTester $t)
    {
        $this->initData();
        $layout3 = new Layout3;
        $date = new Carbon('2014-11-24 00:00:00');
        $timeTable = $layout3->getTimetableOfSingle($this->employee, $this->service, $date);
        $expectedResult = [
            '08:00',
            '08:15',
            '08:30',
            '08:45',
            '09:00',
            '09:15',
            '09:30',
            '09:45',
            '10:00',
            '10:15',
            '10:30',
            '10:45',
            '11:00',
            '11:15',
            '11:30',
            '11:45',
            '12:00',
            '12:15',
            '12:30',
            '12:45',
            '13:00',
            '13:15',
            '13:30',
            '13:45',
            '14:00',
            '14:15',
            '14:30',
            '14:45',
            '15:00',
            '15:15',
            '15:30',
            '15:45',
            '16:00',
            '16:15',
            '16:30',
            '16:45',
            '17:00',
        ];
        $t->assertEquals(array_diff(array_keys($timeTable), $expectedResult), []);
    }
}
