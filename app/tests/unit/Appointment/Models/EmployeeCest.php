<?php namespace Test\Unit\Appointment\Models;

use App\Appointment\Models\AsConsumer;
use App\Appointment\Models\Consumer;
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
        $employee = $this->employee;
        $date = new \Carbon\Carbon('2014-11-24');
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
}
