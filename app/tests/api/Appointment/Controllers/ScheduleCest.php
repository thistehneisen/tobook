<?php
namespace Appointment\Schedule;

use \ApiTester;
use App\Appointment\Models\EmployeeCustomTime;
use App\Appointment\Models\EmployeeDefaultTime;
use Appointment\Traits\Models;
use Carbon\Carbon;

class ScheduleCest
{
    use Models;

    protected $endpoint = '/api/v1.0/as/schedules';

    public function _before(ApiTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();
        $this->_createEmployee();

        // do not use amHttpAuthenticated because route filters are disabled by default
        // and amLoggedAs is just faster!
        $I->amLoggedAs($this->user);
    }

    public function testAllEmployees(ApiTester $I)
    {
        $employeeCount = 10;
        $this->_createEmployee($employeeCount);

        $I->sendGET($this->endpoint);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        foreach ($this->employees as $employee) {
            $I->seeResponseContainsJson(['employee_id' => $employee->id]);
        }

        $employees = $I->grabDataFromJsonResponse('employees');
        $I->assertEquals($employeeCount, count($employees), 'count(employees)');

        $today = Carbon::today();
        foreach ($employees as $employee) {
            $days = array_keys($employee['schedules']);
            $I->assertEquals(1, count($days), 'count(employee.schedules)');
            $I->assertEquals($today->toDateString(), reset($days), 'employee.schedules.{$day}');
        }
    }

    public function testOneEmployee(ApiTester $I)
    {
        $this->_createEmployee(2);
        $I->sendGET($this->endpoint . '?employee_id=' . $this->employees[0]->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['employee_id' => $this->employees[0]->id]);
        $I->dontSeeResponseContainsJson(['employee_id' => $this->employees[1]->id]);

        $employees = $I->grabDataFromJsonResponse('employees');
        $I->assertEquals(1, count($employees), 'count(employees)');

        $today = Carbon::today();
        $employee = reset($employees);
        $days = array_keys($employee['schedules']);
        $I->assertEquals(1, count($days), 'count(employee.schedules)');
        $I->assertEquals($today->toDateString(), reset($days), 'employee.schedules.{$day}');
    }

    public function testOneEmployeeSpecifiedDay(ApiTester $I)
    {
        $date = Carbon::today()->addMonth();

        for ($i = 0; $i <= 7; $i++) {
            $I->sendGET($this->endpoint . '?date=' . $date->toDateString() . '&employee_id=' . $this->employees[0]->id);
            $I->seeResponseCodeIs(200);
            $I->seeResponseIsJson();

            $employees = $I->grabDataFromJsonResponse('employees');
            $employee = reset($employees);
            $days = array_keys($employee['schedules']);
            $I->assertEquals(1, count($days), 'count(employee.schedules)');
            $I->assertEquals($date->toDateString(), reset($days), 'employee.schedules.{$day}');
        }
    }

    public function testOneEmployeeUptoSevenDays(ApiTester $I)
    {
        for ($i = 0; $i <= 7; $i++) {
            $I->sendGET($this->endpoint . '?days=' . $i . '&employee_id=' . $this->employees[0]->id);
            $I->seeResponseCodeIs(200);
            $I->seeResponseIsJson();

            $employees = $I->grabDataFromJsonResponse('employees');
            $employee = reset($employees);
            $days = array_keys($employee['schedules']);
            $I->assertEquals(max(1, $i), count($days), 'count(employee.schedules)');

            $dayObj = Carbon::today();
            foreach ($days as $day) {
                $I->assertEquals($dayObj->toDateString(), $day, 'employee.schedules.{$day}');
                $dayObj->addDay();
            }
        }
    }

    public function testOneEmployeeEightDays(ApiTester $I)
    {
        $I->sendGET($this->endpoint . '?days=8&employee_id=' . $this->employees[0]->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $employees = $I->grabDataFromJsonResponse('employees');
        $employee = reset($employees);
        $days = array_keys($employee['schedules']);
        $I->assertEquals(7, count($days), 'count(employee.schedules)');
    }

    public function testConfigDefaultWorkingTimes(ApiTester $I)
    {
        $I->sendGET($this->endpoint . '?days=7&employee_id=' . $this->employees[0]->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $employees = $I->grabDataFromJsonResponse('employees');
        $employee = reset($employees);

        $this->_assertTimes($I, $this->employees[0]->getDefaultTimes(), $employee['schedules']);
    }

    public function testGeneratedDefaultWorkingTimes(ApiTester $I)
    {
        $defaultTimes = $this->_createDefaultTimes($this->employees[0]);

        $I->sendGET($this->endpoint . '?days=7&employee_id=' . $this->employees[0]->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $employees = $I->grabDataFromJsonResponse('employees');
        $employee = reset($employees);

        $this->_assertTimes($I, $defaultTimes, $employee['schedules']);
    }

    public function testCustomTimes(ApiTester $I)
    {
        $defaultTimes = $this->employees[0]->getDefaultTimes();
        $employeeCustomTimes = $this->_createEmployeeCustomTimes($this->employees[0]);

        $times = [];
        foreach ($defaultTimes as $defaultTime) {
            $times[] = $defaultTime;
        }
        foreach ($employeeCustomTimes as $employeeCustomTime) {
            $times[] = $employeeCustomTime;
        }

        $I->sendGET($this->endpoint . '?days=7&employee_id=' . $this->employees[0]->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $employees = $I->grabDataFromJsonResponse('employees');
        $employee = reset($employees);

        $this->_assertTimes($I, $times, $employee['schedules']);
    }

    public function testFreetimes(ApiTester $I)
    {
        $defaultTimes = $this->employees[0]->getDefaultTimes();
        $employeeFreetimes = $this->_createEmployeeFreetimes($this->employees[0]);

        $I->sendGET($this->endpoint . '?days=7&employee_id=' . $this->employees[0]->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $employees = $I->grabDataFromJsonResponse('employees');
        $employee = reset($employees);

        $this->_assertTimes($I, $defaultTimes, $employee['schedules'], $employeeFreetimes);
    }

    public function testBooking(ApiTester $I)
    {
        $categories = $this->_createCategoryServiceAndExtra(1, 1);
        $category = reset($categories);
        $service = $category->services()->first();

        $tomorrow = Carbon::today()->addDay();
        $this->_book($this->employees[0], $service, array(), $tomorrow, '12:00:00');

        $I->sendGET($this->endpoint);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $employees = $I->grabDataFromJsonResponse('employees');
        $employee = reset($employees);
    }

    protected function _assertTimes(ApiTester $I, $times, $schedules, $employeeFreetimes = array())
    {
        foreach ($schedules as $day => $daySchedules) {
            $dayObj = Carbon::createFromFormat('Y-m-d', $day);

            $timeFound = null;
            $freetimeFound = null;

            $defaultTimeType = \Util::getDayOfWeekText($dayObj->dayOfWeek);
            foreach ($times as $time) {
                if ($time instanceof EmployeeDefaultTime || !empty($time->default)) {
                    if ($time->type == $defaultTimeType) {
                        $timeFound = $time;
                    }
                }
            }

            // run again, looking for custom time
            // custom time takes priority over default time
            foreach ($times as $time) {
                if ($time instanceof EmployeeCustomTime) {
                    if ($time->date == $day) {
                        $timeFound = $time->customTime;
                    }
                }
            }

            foreach ($employeeFreetimes as $freetime) {
                if ($freetime->date == $day) {
                    $freetimeFound = $freetime;
                }
            }

            if (empty($timeFound) || $timeFound->is_day_off) {
                // treat no time = day off
                $I->assertEquals(0, count($daySchedules), 'count(employee.schedules.' . $day . ')');
            } else {
                $firstStartAt = '23:59:59';
                $lastEndAt = '00:00:00';
                $freetimeScheduleFound = false;

                if (!empty($daySchedules)) {
                    foreach ($daySchedules as $schedule) {
                        if ($schedule['start_at'] < $firstStartAt) {
                            $firstStartAt = $schedule['start_at'];
                        }
                        if ($schedule['end_at'] > $lastEndAt) {
                            $lastEndAt = $schedule['end_at'];
                        }

                        if (!empty($freetimeFound)) {
                            if ($schedule['start_at'] >= $freetimeFound->start_at && $schedule['end_at'] <= $freetimeFound->end_at) {
                                $I->assertEquals('freetime', $schedule['type'], sprintf('employee.schedules.%s.%0s.type', $day, $schedule['start_at']));
                                $freetimeScheduleFound = true;
                            }
                        }
                    }
                } else {
                    $firstStartAt = '00:00:00';
                }

                // get hours and minutes only because default working time in config
                // does not have seconds...
                $firstStartAt = substr($firstStartAt, 0, 5);
                $lastEndAt = substr($lastEndAt, 0, 5);

                $I->assertEquals(substr($timeFound->start_at, 0, 5), $firstStartAt, '$firstStartAt');
                $I->assertEquals(substr($timeFound->end_at, 0, 5), $lastEndAt, '$lastEndAt');

                if (!empty($freetimeFound)) {
                    if (!$freetimeScheduleFound) {
                        throw new \Exception(var_export([$freetimeFound->toArray(), $daySchedules], true));
                    }
                    $I->assertTrue($freetimeScheduleFound, '$freetimeScheduleFound');
                }
            }
        }
    }
}
