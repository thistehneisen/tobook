<?php namespace Test\Appointment\Controllers;

use App\Appointment\Models\CustomTime;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeCustomTime;
use App\Appointment\Models\EmployeeFreetime;
use App\Appointment\Models\Booking;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\User;
use Carbon\Carbon;
use \FunctionalTester;
use Config;

/**
 * @group as
 */
class EmployeeCest
{
    use \Test\Traits\Models;
    use \Test\Traits\Booking;

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();
        Employee::boot();

        $this->_createUser();
        $this->_createEmployee();
        $this->employee = $this->employees[0];

        $I->amLoggedAs($this->user);
    }

    public function testIndex(FunctionalTester $I)
    {
        $I->amOnRoute('as.employees.index');
        $I->canSeeResponseCodeIs(200);
        $I->seeNumberOfElements('.employee-row', 1);

        $rowSelector = '#row-' . $this->employee->id;
        $I->seeElement($rowSelector);
        $I->see($this->employee->name, $rowSelector . ' > *');
        $I->see($this->employee->email, $rowSelector . ' > *');
        $I->see($this->employee->phone, $rowSelector . ' > *');
        $I->see(trans('common.active'), $rowSelector . ' > *');
        $I->seeElement($rowSelector . '-edit');
        $I->seeElement($rowSelector . '-delete');

        $I->see(trans('common.all'), '.active');
    }

    public function testIndexInactive(FunctionalTester $I)
    {
        $this->employee->is_active = 0;
        $this->employee->saveOrFail();

        $I->amOnRoute('as.employees.index');
        $I->seeNumberOfElements('.employee-row', 1);

        $rowSelector = '#row-' . $this->employee->id;
        $I->seeElement($rowSelector);
        $I->see(trans('common.inactive'), $rowSelector . ' > *');
    }

    public function testIndexActiveOnly(FunctionalTester $I)
    {
        $this->_createEmployee(2);
        $this->employees[1]->is_active = 0;
        $this->employees[1]->saveOrFail();

        $I->amOnRoute('as.employees.index', ['is_active' => 1]);
        $I->see(trans('common.active'), '.active');

        $I->seeNumberOfElements('.employee-row', 1);
        $I->seeElement('#row-' . $this->employee->id);
    }

    public function testIndexInactiveOnly(FunctionalTester $I)
    {
        $this->_createEmployee(2);
        $this->employees[1]->is_active = 0;
        $this->employees[1]->saveOrFail();

        $I->amOnRoute('as.employees.index', ['is_active' => 0]);
        $I->see(trans('common.inactive'), '.active');

        $I->seeNumberOfElements('.employee-row', 1);
        $I->seeElement('#row-' . $this->employees[1]->id);
    }

    public function testAddSuccess(FunctionalTester $I)
    {
        $name = 'Employee ' . time();
        $phone = '1234567890';
        $email = 'employee' . time() . '@varaa.com';
        $description = $name . ' description';

        $I->amOnRoute('as.employees.upsert');
        $I->fillField('name', $name);
        $I->fillField('phone', $phone);
        $I->fillField('email', $email);
        $I->fillField('description', $description);
        $I->attachFile('avatar', 'avatar.jpg');
        $I->click('#btn-save-employee');

        $I->seeCurrentRouteIs('as.employees.index');
        $I->seeNumberOfElements('.employee-row', 2);

        $employees = Employee::ofCurrentUser()->where('id', '<>', $this->employee->id)->get();
        $I->assertEquals(1, count($employees), 'employees');
        foreach ($employees as $employee) {
            $I->assertEquals($name, $employee->name, 'name');
            $I->assertEquals($phone, $employee->phone, 'phone');
            $I->assertEquals($email, $employee->email, 'email');
            $I->assertEquals($description, $employee->description, 'description');

            $I->assertEquals(1, $employee->is_active, 'is_active');
            $I->assertEquals(0, $employee->is_subscribed_email, 'is_subscribed_email');
            $I->assertEquals(0, $employee->is_subscribed_sms, 'is_subscribed_sms');

            $I->assertNotEmpty($employee->avatar, 'avatar');
            $I->assertTrue(file_exists(public_path() . '/' . $employee->getAvatarPath()), 'avatar file exists');
        }
    }

    public function testAddWithoutName(FunctionalTester $I)
    {
        $name = 'Employee ' . time();
        $phone = '1234567890';
        $email = 'employee' . time() . '@varaa.com';
        $description = $name . ' description';

        $I->amOnRoute('as.employees.upsert');
        $I->fillField('phone', $phone);
        $I->fillField('email', $email);
        $I->fillField('description', $description);
        $I->click('#btn-save-employee');

        $I->seeCurrentRouteIs('as.employees.upsert');
        $I->seeSessionHasErrors();

        $employees = Employee::ofCurrentUser()->where('id', '<>', $this->employee->id)->get();
        $I->assertEquals(0, count($employees), 'employees');
    }

    public function testAddWithoutPhone(FunctionalTester $I)
    {
        $name = 'Employee ' . time();
        $email = 'employee' . time() . '@varaa.com';
        $description = $name . ' description';

        $I->amOnRoute('as.employees.upsert');
        $I->fillField('name', $name);
        $I->fillField('email', $email);
        $I->fillField('description', $description);
        $I->click('#btn-save-employee');

        $I->seeCurrentRouteIs('as.employees.upsert');
        $I->seeSessionHasErrors();

        $employees = Employee::ofCurrentUser()->where('id', '<>', $this->employee->id)->get();
        $I->assertEquals(0, count($employees), 'employees');
    }

    public function testAddWithoutEmail(FunctionalTester $I)
    {
        $name = 'Employee ' . time();
        $phone = '1234567890';
        $description = $name . ' description';

        $I->amOnRoute('as.employees.upsert');
        $I->fillField('name', $name);
        $I->fillField('phone', $phone);
        $I->fillField('description', $description);
        $I->click('#btn-save-employee');

        $I->seeCurrentRouteIs('as.employees.upsert');
        $I->seeSessionHasErrors();

        $employees = Employee::ofCurrentUser()->where('id', '<>', $this->employee->id)->get();
        $I->assertEquals(0, count($employees), 'employees');
    }

    public function testDelete(FunctionalTester $I)
    {
        $I->amOnRoute('as.employees.delete', ['id' => $this->employee->id]);
        $I->seeCurrentRouteIs('as.employees.index');

        $employee = Employee::find($this->employee->id);
        $I->assertEmpty($employee, 'employee has been deleted');
    }

    public function testBulkDelete(FunctionalTester $I)
    {
        $this->_createEmployee(2);

        $employeeIds = [];
        foreach ($this->employees as $employee) {
            $employeeIds[] = $employee->id;
        }

        $I->sendAjaxPostRequest(route('as.employees.bulk'), [
            'action' => 'destroy',
            'ids' => $employeeIds,
        ]);

        $employee1 = Employee::find($employeeIds[0]);
        $I->assertEmpty($employee1, 'employee 1 has been deleted');
        $employee2 = Employee::find($employeeIds[1]);
        $I->assertEmpty($employee2, 'employee 2 has been deleted');
    }

    public function testDefaultTime(FunctionalTester $I)
    {
        $I->amOnRoute('as.employees.defaultTime.get', ['id' => $this->employee->id]);

        $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

        $values = [];
        foreach ($days as $i => $day) {
            $values[$day]['start_hour'] = $i;
            $values[$day]['start_minute'] = $i * 5;
            $values[$day]['end_hour'] = $i + 8;
            $values[$day]['end_minute'] = 55 - $i * 5;
            $values[$day]['is_day_off'] = rand(0, 1);
        }

        foreach ($days as $i => $day) {
            $I->seeOptionIsSelected('start_hour[' . $day . ']', 8);
            $I->seeOptionIsSelected('start_minute[' . $day . ']', 0);
            $I->seeOptionIsSelected('end_hour[' . $day . ']', 18);
            $I->seeOptionIsSelected('end_minute[' . $day . ']', 0);
            $I->dontSeeCheckboxIsChecked('is_day_off[' . $day . ']');

            $I->selectOption('start_hour[' . $day . ']', $values[$day]['start_hour']);
            $I->selectOption('start_minute[' . $day . ']', $values[$day]['start_minute']);
            $I->selectOption('end_hour[' . $day . ']', $values[$day]['end_hour']);
            $I->selectOption('end_minute[' . $day . ']', $values[$day]['end_minute']);
            if (!empty($values[$day]['is_day_off'])) {
                $I->checkOption('is_day_off[' . $day . ']');
            }
        }

        $I->click('#btn-save');

        $employee = Employee::find($this->employee->id);
        $defaultTimes = $employee->getDefaultTimes();
        foreach ($defaultTimes as $defaultTime) {
            $day = $defaultTime->type;

            $I->assertEquals($values[$day]['start_hour'], $defaultTime->getStartHourIndex(), $day . ' start hour');
            $I->assertEquals($values[$day]['start_minute'], $defaultTime->getStartMinuteIndex(), $day . ' start minute');
            $I->assertEquals($values[$day]['end_hour'], $defaultTime->getEndHourIndex(), $day . ' end hour');
            $I->assertEquals($values[$day]['end_minute'], $defaultTime->getEndMinuteIndex(), $day . ' end minute');
            $I->assertEquals($values[$day]['is_day_off'], $defaultTime->is_day_off, $day . ' is day off');
        }
    }

    public function testCustomTime(FunctionalTester $I)
    {
        $customTime = $this->_createCustomTime();

        $I->amOnRoute('as.employees.customTime');
        $I->canSeeResponseCodeIs(200);
        $I->seeNumberOfElements('.custom-time-row', 1);

        $rowSelector = '#row-' . $customTime->id;
        $I->seeElement($rowSelector);
        $I->see($customTime->name, $rowSelector . ' > *');
        $I->see($customTime->start_at, $rowSelector . ' > *');
        $I->see($customTime->end_at, $rowSelector . ' > *');
        $I->see(trans('common.no'), $rowSelector . ' > *');
        $I->seeElement($rowSelector . '-edit');
        $I->seeElement($rowSelector . '-delete');
    }

    public function testCustomTimeAdd(FunctionalTester $I)
    {
        $name = 'Custom Time ' . time();
        $startAt = '08:00:00';
        $endAt = '18:00:00';

        $I->amOnRoute('as.employees.customTime');
        $I->fillField('name', $name);
        $I->fillField('start_at', $startAt);
        $I->fillField('end_at', $endAt);
        $I->click('#btn-save');

        $I->seeCurrentRouteIs('as.employees.customTime');
        $I->seeNumberOfElements('.custom-time-row', 1);

        $customTimes = CustomTime::ofCurrentUser()->get();
        $I->assertEquals(1, count($customTimes), 'custom times');
        foreach ($customTimes as $customTime) {
            $I->assertEquals($name, $customTime->name, 'name');
            $I->assertEquals($startAt, $customTime->start_at, 'start_at');
            $I->assertEquals($endAt, $customTime->end_at, 'end_at');
            $I->assertEquals(0, $customTime->is_day_off, 'is_day_off');
        }
    }

    public function testCustomTimeEdit(FunctionalTester $I)
    {
        $customTime = $this->_createCustomTime();

        $I->amOnRoute('as.employees.customTime.upsert', ['id' => $customTime->id]);
        $I->checkOption('is_day_off');
        $I->click('#btn-save');

        $I->seeCurrentRouteIs('as.employees.customTime');
        $I->seeNumberOfElements('.custom-time-row', 1);

        $customTime = CustomTime::find($customTime->id);
        $I->assertEquals(1, $customTime->is_day_off, 'is_day_off');
    }

    public function testCustomTimeDelete(FunctionalTester $I)
    {
        $customTime = $this->_createCustomTime();
        $I->assertNotEmpty($customTime, 'custom time has been created');

        $I->amOnRoute('as.employees.customTime.delete', ['id' => $customTime->id]);
        $I->seeCurrentRouteIs('as.employees.customTime');
        $I->seeNumberOfElements('.custom-time-row', 0);

        $customTime = CustomTime::find($customTime->id);
        $I->assertEmpty($customTime, 'custom time has been deleted');
    }

    public function testEmployeeCustomTime(FunctionalTester $I)
    {
        $this->_createEmployee(2);

        $startAt = new Carbon('08:00:00');
        $endAt = new Carbon('18:00:00');
        $employee = $this->employees[1];
        $date = Carbon::today()->addMonth();
        $customTime = $this->_createCustomTime($startAt->format('H:i:s'), $endAt->format('H:i:s'), $employee, $date);

        $I->amOnRoute('as.employees.employeeCustomTime', [
            'employeeId' => $employee->id,
            'date' => $date->format('Y-m'),
        ]);
        $I->seeOptionIsSelected('employees', $employee->name);
        $I->seeOptionIsSelected('custom_times[' . $date->toDateString() . ']', $customTime->name . sprintf(' (%s - %s)', $startAt->format('H:i'),  $endAt->format('H:i')));

        $firstOfMonth = with(clone $date)->firstOfMonth();
        for ($i = 0; $i < $date->daysInMonth; $i++) {
            if ($firstOfMonth == $date) {
                continue;
            }

            $I->seeOptionIsSelected('custom_times[' . $firstOfMonth->toDateString() . ']', trans('common.options_select'));
            $firstOfMonth->addDay();
        }

        $I->click('#prev-month');
        $I->seeCurrentRouteIs('as.employees.employeeCustomTime', [
            'employeeId' => $employee->id,
            'date' => with(clone $date)->subMonth()->format('Y-m'),
        ]);

        $I->click('#next-month');
        $I->seeCurrentRouteIs('as.employees.employeeCustomTime', [
            'employeeId' => $employee->id,
            'date' => $date->format('Y-m'),
        ]);
    }

    public function testEmployeeCustomTimeAdd(FunctionalTester $I)
    {
        $startAt = '08:00:00';
        $endAt = '18:00:00';
        $employee = $this->employee;
        $date = Carbon::today()->addMonth();
        $customTime = $this->_createCustomTime($startAt, $endAt);

        $I->amOnRoute('as.employees.employeeCustomTime', [
            'employeeId' => $employee->id,
            'date' => $date->format('Y-m'),
        ]);
        $I->seeOptionIsSelected('employees', $employee->name);
        $selector = 'custom_times[' . $date->toDateString() . ']';
        $I->seeOptionIsSelected($selector, trans('common.options_select'));
        $I->selectOption($selector, $customTime->id);
        $I->click('#btn-save');

        $employeeCustomTime = EmployeeCustomTime::where('custom_time_id', $customTime->id)
            ->where('employee_id', $employee->id)
            ->first();
        $I->assertNotEmpty($employeeCustomTime, 'employee custom time has been created');
    }

    public function testEmployeeCustomTimeAfterDelete(FunctionalTester $I)
    {
        $startAt = '08:00:00';
        $endAt = '18:00:00';
        $employee = $this->employee;
        $date = Carbon::today()->addMonth();
        $customTime = $this->_createCustomTime($startAt, $endAt);

        $I->amOnRoute('as.employees.employeeCustomTime', [
            'employeeId' => $employee->id,
            'date' => $date->format('Y-m'),
        ]);
        $I->seeOptionIsSelected('employees', $employee->name);
        $selector = 'custom_times[' . $date->toDateString() . ']';
        $I->seeOptionIsSelected($selector, trans('common.options_select'));
        $I->selectOption($selector, $customTime->id);
        $I->click('#btn-save');

        $employeeCustomTime = EmployeeCustomTime::where('custom_time_id', $customTime->id)
            ->where('employee_id', $employee->id)
            ->first();
        $I->assertNotEmpty($employeeCustomTime, 'employee custom time has been created');

        //Delete the custome time above

        $I->amOnRoute('as.employees.customTime.delete', [
            'customTimeId' => $customTime->id
        ]);

        $testCustomTime = CustomTime::find($customTime->id);
        $I->assertEmpty($testCustomTime, 'custom time has been deleted');

        $employeeCustomTimes = EmployeeCustomTime::where('custom_time_id', $customTime->id)->get();
        $I->assertEmpty($employeeCustomTimes);

        //After delete check can we access the employee work-shift planning or not
        $I->amOnRoute('as.employees.employeeCustomTime', [
            'employeeId' => $employee->id,
            'date' => $date->format('Y-m'),
        ]);

        $I->seeOptionIsSelected('employees', $employee->name);
        $I->seeOptionIsSelected($selector, trans('common.options_select'));

        $I->amOnRoute('as.employee', [
            'employeeId' => $employee->id,
            'date' => $date->format('Y-m-d'),
        ]);

    }

    public function testEmployeeCustomTimeReplace(FunctionalTester $I)
    {
        $startAt = new Carbon('08:00:00');
        $endAt = new Carbon('18:00:00');
        $employee = $this->employee;
        $date = Carbon::today()->addMonth();
        $customTime = $this->_createCustomTime($startAt->format('H:i:s'), $endAt->format('H:i:s'), $employee, $date);

        $startAt2 = '13:00:00';
        $customTime2 = $this->_createCustomTime($startAt2, $endAt);

        $I->amOnRoute('as.employees.employeeCustomTime', [
            'employeeId' => $employee->id,
            'date' => $date->format('Y-m'),
        ]);
        $I->seeOptionIsSelected('employees', $employee->name);
        $selector = 'custom_times[' . $date->toDateString() . ']';
        $I->seeOptionIsSelected($selector, $customTime->name . sprintf(' (%s - %s)', $startAt->format('H:i'),  $endAt->format('H:i')));
        $I->selectOption($selector, $customTime2->id);
        $I->click('#btn-save');

        $employeeCustomTime = EmployeeCustomTime::where('date', $date->toDateString())
            ->where('employee_id', $employee->id)
            ->first();
        $I->assertEquals($customTime2->id, $employeeCustomTime->custom_time_id, 'custom_time_id');
    }

    public function testEmployeeCustomTimeDelete(FunctionalTester $I)
    {
        $startAt = new Carbon('08:00:00');
        $endAt = new Carbon('18:00:00');
        $employee = $this->employee;
        $date = Carbon::today()->addMonth();
        $customTime = $this->_createCustomTime($startAt->format('H:i:s'), $endAt->format('H:i:s'), $employee, $date);

        $I->amOnRoute('as.employees.employeeCustomTime', [
            'employeeId' => $employee->id,
            'date' => $date->format('Y-m'),
        ]);
        $I->seeOptionIsSelected('employees', $employee->name);
        $selector = 'custom_times[' . $date->toDateString() . ']';
        $I->seeOptionIsSelected($selector, $customTime->name . sprintf(' (%s - %s)', $startAt->format('H:i'),  $endAt->format('H:i')));
        $I->selectOption($selector, 0);
        $I->click('#btn-save');

        $employeeCustomTime = EmployeeCustomTime::where('date', $date->toDateString())
            ->where('employee_id', $employee->id)
            ->first();
        $I->assertEmpty($employeeCustomTime, 'employee custom time has been deleted');
    }

    /**
     * This test is used for preventing create a freetime which is overllapped with a booking
     * Related to issue #237
     */
    public function testAddEmployeeFreeTime(FunctionalTester $I)
    {
        $user = User::find(70);
        $I->amLoggedAs($user);
        $category = ServiceCategory::find(105);
        $booking = $this->_book($user, $category);
        $I->assertNotEmpty($booking, 'booking has been found');
        //get employee of the booking above
        $employee = $booking->firstBookingService()->employee;
        $I->assertNotEmpty($employee, 'employee has been found');

        $inputs = [
            'date' => $booking->date,
            'employees' => $employee->id,
            'start_at' => $booking->start_at,
            'end_at'   => $booking->end_at,
        ];

        $I->sendPOST(route('as.employees.freetime.add'), $inputs);
        $I->seeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $json = $I->grabDataFromJsonResponse();
        $I->assertNotEmpty($json, 'json not empty');
        $I->assertFalse($json['success'], 'You shall not pass');
        $I->assertNotEmpty($json['message']);
        //for other test
        $I->amLoggedAs($this->user);
    }

    /**
     * @param string $startAt
     * @param string $endAt
     * @param Employee $employee
     * @param Carbon $date
     * @return CustomTime
     */
    private function _createCustomTime($startAt = '08:00:00', $endAt = '18:00:00', Employee $employee = null, Carbon $date = null)
    {
        $customTime = new CustomTime([
            'name' => sprintf('%s till %s', $startAt, $endAt),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'is_day_off' => 0,
        ]);
        $customTime->user()->associate($this->user);
        $customTime->saveOrFail();

        if ($employee != null) {
            if ($date == null) {
                $date = Carbon::today();
            }
            $employeeCustomTime = new EmployeeCustomTime(['date' => $date->toDateString()]);
            $employeeCustomTime->customTime()->associate($customTime);
            $employeeCustomTime->employee()->associate($employee);
            $employeeCustomTime->save();
        }

        return $customTime;
    }
}
