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
    private $employee = null;
    private $category = null;
    private $service  = null;
    private $user = null;

    public function testTimetableOfSingle(UnitTester $t)
    {
        $this->initData();
        $layout3 = new Layout3;
        $date = new Carbon('2014-11-24 00:00:00');
        $timeTable = $layout3->getTimetableOfSingle($this->employee, $this->service, $date);
        // Expected result with custome time and freetime
        // Custom time from 9:00 to 17:00 and have freetime from 13:00 to 14:00
        $result = [
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
        $t->assertEquals(array_keys($timeTable), $result);
    }

    private function initData()
    {
        $this->user = User::find(70);
        Employee::where('id', 63)->forceDelete();
        $this->employee = new Employee([
            'name' => 'Anne K',
            'email' => 'annek@varaa.com',
            'phone' => '1234567890',
            'is_active' => 1,
        ]);
        $this->employee->id = 63;
        $this->employee->user()->associate($this->user);
        $this->employee->saveOrFail();

        ServiceCategory::where('id', 106)->forceDelete();
        $this->category = new ServiceCategory([
            'name' => 'Hiusjuuritutkimus',
            'is_show_front' => 1,
        ]);
        $this->category->id = 106;
        $this->category->user()->associate($this->user);
        $this->category->saveOrFail();

        $this->service = new Service([
            'name' => 'Hiusjuuritutkimus',
            'length' => 60,
            'during' => 45,
            'after' => 15,
            'price' => 35,
            'is_active' => 1,
        ]);

        $this->service->user()->associate($this->user);
        $this->service->category()->associate($this->category);
        $this->service->saveOrFail();
        $this->service->employees()->attach($this->employee);

        //Init custome time
        $customTime = new CustomTime;

        $customTime->fill([
                'name'       => '9:00 to 17:00',
                'start_at'   => '09:00',
                'end_at'     => '17:00',
                'is_day_off' => false
            ]);
        $customTime->user()->associate($this->user);
        $customTime->save();

        //Add employee custom time

        $employeeCustomTime =  EmployeeCustomTime::getUpsertModel($this->employee->id, '2014-11-24 00:00:00');
        $employeeCustomTime->fill([
            'date' =>  '2014-11-24 00:00:00'
        ]);
        $employeeCustomTime->employee()->associate($this->employee);
        $employeeCustomTime->customTime()->associate($customTime);
        $employeeCustomTime->save();

        //Add employee freetime
        $employeeFreetime = new EmployeeFreetime();
        $employeeFreetime->fill([
            'date' => '2014-11-24',
            'start_at' => '13:00',
            'end_at'=>'14:00',
            'description' => ''
        ]);
        $employeeFreetime->user()->associate($this->user);
        $employeeFreetime->employee()->associate($this->employee);
        $employeeFreetime->save();
    }
}
