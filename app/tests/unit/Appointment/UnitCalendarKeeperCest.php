<?php namespace Appointment\Models;

use App\Core\Models\User;
use App\Appointment\Models\NAT\CalendarKeeper;
use Carbon\Carbon;
use \UnitTester;

/**
 * @group as
 */
class UnitCalendarKeeperCest
{
    public function testNextTimeSlots(UnitTester $t)
    {
        $user = User::find(70);
        $date = Carbon::createFromFormat('Y-m-d', '2014-10-10');
        $NAT = CalendarKeeper::nextTimeSlots($user, $date);
        $t->assertGreaterThan(0, count($NAT));
        $t->assertEquals($NAT[0]['time'], '8:15');
    }

    public function testDefaultWorkingTimes(UnitTester $t)
    {
        $user = User::find(70);
        $date = Carbon::createFromFormat('Y-m-d', '2014-10-10');
        $workingTimes = CalendarKeeper::getDefaultWorkingTimes($user, $date);
        $t->assertEquals($workingTimes, [
            8  => [0, 15, 30, 45],
            9  => [0, 15, 30, 45],
            10 => [0, 15, 30, 45],
            11 => [0, 15, 30, 45],
            12 => [0, 15, 30, 45],
            13 => [0, 15, 30, 45],
            14 => [0, 15, 30, 45],
            15 => [0, 15, 30, 45],
            16 => [0, 15, 30, 45],
            17 => [0, 15, 30, 45],
            18 => [0, 15, 30, 45],
            19 => [0, 15, 30, 45]
        ]);
        $t->assertEquals($workingTimes, $user->getASDefaultWorkingTimes($date));
    }
}
