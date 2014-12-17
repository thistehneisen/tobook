<?php namespace Test\Appointment\Models;

use App\Core\Models\User;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeDefaultTime;
use App\Appointment\Models\NAT\CalendarKeeper;
use Carbon\Carbon;
use \UnitTester;

/**
 * @group as
 */
class CalendarKeeperCest
{
    private $user;
    private $date;
    private $employee;

    public function _before(UnitTester $t)
    {
        $this->user = User::find(70);
        $this->date = Carbon::today();

        $this->employee = new Employee([
            'name' => 'Employee 500',
            'email' => 'employee_500@varaa.com',
            'phone' => '1234567890',
            'is_active' => 1,
        ]);
        $this->employee->user()->associate($this->user);
        $this->employee->saveOrFail();

        foreach(['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day) {
            $time = new EmployeeDefaultTime([
                'type' => $day,
                'start_at' => '07:00:00',
                'end_at' => '21:00:00',
                'is_day_off' => false
            ]);
            $time->employee()->associate($this->employee);
            $time->saveOrFail();
        }
    }

    public function testNextTimeSlots(UnitTester $t)
    {
        $NAT = CalendarKeeper::nextTimeSlots($this->user, $this->date);
        $t->assertGreaterThan(0, count($NAT));
        $t->assertEquals($NAT[0]['time'], '07:15');
    }

    public function testDefaultWorkingTimes(UnitTester $t)
    {
        $workingTimes = CalendarKeeper::getDefaultWorkingTimes($this->user, $this->date);
        $t->assertEquals($workingTimes, [
            7  => [0, 15, 30, 45],
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
            19 => [0, 15, 30, 45],
            20 => [0, 15, 30, 45],
        ]);
    }

    public function testDefaultWorkingTimesForEmployees(UnitTester $t)
    {
        $workingTimes = CalendarKeeper::getDefaultWorkingTimes($this->user, $this->date, true, $this->employee);
        $t->assertEquals($workingTimes, [
            7  => [0, 15, 30, 45],
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
            19 => [0, 15, 30, 45],
            20 => [0, 15, 30, 45],
        ]);

        $workingTimes = CalendarKeeper::getDefaultWorkingTimes($this->user, $this->date, true, Employee::find(64));
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
            19 => [0, 15, 30, 45],
        ]);
    }
}
