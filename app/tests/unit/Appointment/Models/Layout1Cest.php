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
class UnitLayout1Cest
{

    use \Appointment\Traits\Models;

    public function testTimetableOfSingleCustom(UnitTester $t)
    {
        $this->initData();
        $this->initCustomTime();
    }

}
