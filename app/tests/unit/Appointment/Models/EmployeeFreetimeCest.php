<?php namespace Test\Appointment\Models;

use App\Appointment\Models\EmployeeFreetime;
use \UnitTester;

/**
 * @group as
 */
class EmployeeFreetimeCest
{
    public function testGetStartTime(UnitTester $I)
    {
        $freetime = new EmployeeFreetime;
        $freetime->date = '2014-11-15';
        $freetime->start_at = '9:00';

        $I->assertTrue($freetime->start_time instanceof \Carbon\Carbon);
        $I->assertEquals($freetime->start_time->toTimeString(), '09:00:00');
        $I->assertEquals($freetime->start_time->toDateString(), '2014-11-15');
    }

    public function testGetEndTime(UnitTester $I)
    {
        $freetime = new EmployeeFreetime;
        $freetime->date = '2014-11-15';
        $freetime->end_at = '10:00';

        $I->assertTrue($freetime->end_time instanceof \Carbon\Carbon);
        $I->assertEquals($freetime->end_time->toTimeString(), '10:00:00');
        $I->assertEquals($freetime->end_time->toDateString(), '2014-11-15');
    }
}
