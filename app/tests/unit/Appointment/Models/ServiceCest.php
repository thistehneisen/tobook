<?php namespace Test\Appointment\Models;

use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\EmployeeService;
use App\Core\Models\User;
use \UnitTester;
use Test\Traits\Models;
/**
 * @group as
 */
class UnitServiceCest
{
    use Models;

    public function testServiceLength(UnitTester $t)
    {
        $service = new Service;
        $service->fill([
            'name' => 'foo',
            'description' => 'bar',
            'before'=> 15,
            'during'=> 30,
            'after' => 15,
        ]);
        $service->user()->associate(User::find(70));
        $service->category()->associate(ServiceCategory::find(105));
        $service->setLength();
        $service->save();

        $t->assertEquals($service->name, 'foo');
        $t->assertEquals($service->length, 60);
    }

    public function testServiceTime(UnitTester $t)
    {
        if(empty($this->category)) {
            $this->initData();
            $this->initCustomTime();
        }
        $user = User::find(70);
        $category = $this->category;
        $service = $category->services()->first();
        $employee = $this->employee;

        $t->assertEquals($service->length, 60);
        $t->assertEquals($service->before, 0);
        //This service has 15 mins after the actual service length
        $t->assertEquals($service->after, 15);

        $cooked = array(
            array('id'=> -1, 'name'=> '-- Valitse --', 'length'=> 0, 'description'=> ''),
            array('id'=> 'default', 'name'=> 60, 'length'=> 60, 'price' =>35, 'description'=> ''),
        );

        $service = Service::find($service->id);
        $data = $service->getServiceTimesData();
        $t->assertNotEmpty($data);
        $t->assertEquals($data, $cooked);

        //Adding plustime
        $plustime = 30;
        $service->employees()->detach($employee);
        $employeeService = new EmployeeService();
        $employeeService->service()->associate($service);
        $employeeService->employee()->associate($employee);
        $employeeService->plustime = $plustime;
        $employeeService->save();

        $cooked = array(
            array('id'=> -1, 'name'=> '-- Valitse --', 'length'=> 0, 'description'=> ''),
            array('id'=> 'default', 'name'=> 60+$plustime, 'length'=> 60+$plustime, 'price' =>35, 'description'=> ''),
        );

        $data = $service->getServiceTimesData($employee->id);
        $t->assertNotEmpty($data);
        $t->assertEquals($data, $cooked);
    }
}
