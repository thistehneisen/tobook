<?php namespace Test\Api\Appointment\Controllers;

use \ApiTester;
use App\Appointment\Models\Employee;
use Test\Traits\Models;
use Carbon\Carbon;

/**
 * @group as
 */
class EmployeeCest
{
    use Models;

    protected $employeesEndpoint = '/api/v1.0/as/employees';

    public function _before(ApiTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();

        // do not use amHttpAuthenticated because route filters are disabled by default
        // and amLoggedAs is just faster!
        $I->amLoggedAs($this->user);
    }

    public function testIndex(ApiTester $I)
    {
        $employeeCount = 3;
        $this->_createEmployee($employeeCount);

        $I->sendGET($this->employeesEndpoint);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals($employeeCount, $pagination['total'], "\$pagination['total']");
        $I->assertEquals(1, $pagination['page'], "\$pagination['page']");
        $I->assertEquals(1, $pagination['last_page'], "\$pagination['last_page']");

        $employeesData = $I->grabDataFromJsonResponse('data');
        $I->assertEquals($employeeCount, count($employeesData), 'count($employeesData)');
        $employees = Employee::ofCurrentUser()->get();
        $I->assertEquals($employeeCount, count($employees), 'count($employees)');
        foreach ($employees as $employee) {
            $employeeDataFound = false;

            foreach ($employeesData as $employeeData) {
                if ($employee->id == $employeeData['employee_id']) {
                    $employeeDataFound = true;
                }
            }

            $I->assertTrue($employeeDataFound, '$employeeDataFound');
        }
    }

    public function testStore(ApiTester $I)
    {
        $name = 'Employee ' . time();
        $email = 'employee'. time() . '@varaa.com';
        $phone = time();

        $I->sendPOST($this->employeesEndpoint, [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $employeeData = $I->grabDataFromJsonResponse('data');
        $I->assertEquals($name, $employeeData['employee_name'], "\$employeeData['employee_name']");
        $I->assertEquals($email, $employeeData['employee_email'], "\$employeeData['employee_email']");
        $I->assertEquals($phone, $employeeData['employee_phone'], "\$employeeData['employee_phone']");

        $employee = Employee::ofCurrentUser()->findOrFail($employeeData['employee_id']);
        $I->assertNotEmpty($employee);
        $this->_assertEmployee($I, $employee, $employeeData);
    }

    public function testShow(ApiTester $I)
    {
        $this->_createEmployee();
        $employee = $this->employees[0];

        $I->sendGET($this->employeesEndpoint . '/' . $employee->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $employeeData = $I->grabDataFromJsonResponse('data');
        $this->_assertEmployee($I, $employee, $employeeData);
    }

    public function testUpdate(ApiTester $I)
    {
        $this->_createEmployee();
        $employee = $this->employees[0];

        $name = 'Employee Edited ' . time();
        $email = 'new_employee' . time() . '@varaa.com';
        $phone = time();

        $I->sendPUT($this->employeesEndpoint . '/' . $employee->id, [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => false]);

        $employeeData = $I->grabDataFromJsonResponse('data');
        $I->assertEquals($employee->id, $employeeData['employee_id'], "\$employeeData['employee_id']");
        $I->assertEquals($name, $employeeData['employee_name'], "\$employeeData['employee_name']");
        $I->assertEquals($email, $employeeData['employee_email'], "\$employeeData['employee_email']");
        $I->assertEquals($phone, $employeeData['employee_phone'], "\$employeeData['employee_phone']");

        $employee = Employee::ofCurrentUser()->findOrFail($employeeData['employee_id']);
        $this->_assertEmployee($I, $employee, $employeeData);
    }

    public function testDestroy(ApiTester $I)
    {
        $this->_createEmployee();
        $employee = $this->employees[0];

        $I->sendDELETE($this->employeesEndpoint . '/' . $employee->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => false]);

        $employee = Employee::ofCurrentUser()->find($employee->id);
        $I->assertEmpty($employee, '$employee');
    }

    protected function _assertEmployee(\ApiTester $I, Employee $employee, array $employeeData) {
        $I->assertEquals($employee->name, $employeeData['employee_name'], "\$employeeData['employee_name']");
        $I->assertEquals($employee->email, $employeeData['employee_email'], "\$employeeData['employee_email']");
        $I->assertEquals($employee->phone, $employeeData['employee_phone'], "\$employeeData['employee_phone']");
    }

}
