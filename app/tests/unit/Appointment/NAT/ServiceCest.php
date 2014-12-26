<?php namespace Test\Appointment\NAT;

use UnitTester, App, NAT;
use Mockery as m;
use App\Appointment\Models\Employee;

class ServiceCest
{
    public function _after()
    {
        m::close();
    }

    public function testGetEmployeeObject(UnitTester $i)
    {
        $employee = new Employee();
        $mock = m::mock('App\Appointment\Models\Employee[find]');
        $mock->shouldReceive('find')->with(\Mockery::not(999))->andReturn(null);
        $mock->shouldReceive('find')->with(999)->andReturn($employee);
        $mock->shouldReceive('find')->with(3)->andReturn(new Employee());

        // Swap the mock
        App::instance('App\Appointment\Models\Employee', $mock);

        $ids = [1, 2, 3];
        $i->assertEquals(null, NAT::getEmployeeObject($ids), 'Cannot find any employee in the ID list');

        $ids = [1, 2, 999, 3];
        $i->assertEquals($employee, NAT::getEmployeeObject($ids), 'Found one employee in the list');

        $ids = [1, 2, 999, 3];
        $i->assertEquals($employee, NAT::getEmployeeObject($ids), 'Return the first employee found');
    }
}
