<?php
namespace Test\Traits;

use App\Appointment\Models\CustomTime;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeCustomTime;
use App\Appointment\Models\EmployeeDefaultTime;
use App\Appointment\Models\EmployeeFreetime;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Consumers\Models\Campaign;
use App\Consumers\Models\Consumer;
use App\Consumers\Models\Group;
use App\Core\Models\Business;
use App\Core\Models\Role;
use App\Core\Models\User;
use Carbon\Carbon;

trait Models
{
    protected $user = null;
    protected $employees = array();

    protected function _createUser($withBusiness = true)
    {
        if (!empty($this->user)) {
            return;
        }

        $this->user = new User([
            'email' => 'user_' . time() . '@varaa.com',
        ]);
        $this->user->password = 123456;
        $this->user->password_confirmation = 123456;
        $this->user->confirmed = 1;
        $this->user->save();
        $this->user->attachRole(Role::user());

        if ($withBusiness) {
            $business = new Business([
                'name' => 'Business Name',
                'address' => 'An Address',
                'city' => 'Some City',
                'postcode' => 10000,
                'country' => 'Finland',
                'phone' => '1234567890',
            ]);
            $business->size = 1;
            $business->is_activated = 1;
            $business->user()->associate($this->user);
            $business->save();
        }
    }

    protected function _createEmployee($employeeCount = 1)
    {
        if (empty($this->user)) {
            $this->_createUser(true);
        }

        for ($i = 0; $i < $employeeCount; $i++) {
            if (!empty($this->employees[$i])) {
                continue;
            }

            $this->employees[$i] = new Employee([
                'name' => 'Employee ' . $i,
                'email' => 'employee_' . $i . $this->user->id . '@varaa.com',
                'phone' => '1234567890',
                'is_active' => 1,
            ]);
            $this->employees[$i]->user()->associate($this->user);
            $this->employees[$i]->saveOrFail();
        }
    }

    protected function _createDefaultTimes(Employee $employee)
    {
        $defaultTimes = [];

        foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $i => $defaultTimeType) {
            $defaultTime = new EmployeeDefaultTime([
                'type' => $defaultTimeType,
                'start_at' => '00:00:00',
                'end_at' => sprintf('%02d:00:00', $i),
                'is_day_off' => ($defaultTimeType == 'fri' ? 1 : 0),
            ]);
            $defaultTime->employee()->associate($employee);
            $defaultTime->save();

            $defaultTimes[] = $defaultTime;
        }

        return $defaultTimes;
    }

    protected function _createEmployeeCustomTimes(Employee $employee)
    {
        if (empty($this->user)) {
            $this->_createUser(true);
        }

        $employeeCustomTimes = [];
        $today = Carbon::today();

        for ($i = 0; $i < 7; $i++) {
            $dayObj = with(clone $today)->addDays($i);

            if ($dayObj->dayOfWeek === Carbon::MONDAY) {
                // no custom time for Monday
                continue;
            }

            $customTime = new CustomTime([
                'name' => 'Custom Time ' . $i,
                'start_at' => '00:00:00',
                'end_at' => sprintf('%02d:00:00', $i),
                'is_day_off' => ($i == 5 ? 1 : 0),
            ]);
            $customTime->user()->associate($this->user);
            $customTime->saveOrFail();

            $employeeCustomTime = new EmployeeCustomTime([
                'date' => $dayObj->toDateString(),
            ]);
            $employeeCustomTime->employee()->associate($employee);
            $employeeCustomTime->customTime()->associate($customTime);
            $employeeCustomTime->save();

            $employeeCustomTimes[] = $employeeCustomTime;
        }

        return $employeeCustomTimes;
    }

    protected function _createEmployeeFreetimes(Employee $employee)
    {
        if (empty($this->user)) {
            $this->_createUser(true);
        }

        $employeeFreetimes = [];
        $today = Carbon::today();

        for ($i = 0; $i < 7; $i++) {
            $dayObj = with(clone $today)->addDays($i);

            if ($dayObj->dayOfWeek === Carbon::TUESDAY) {
                // no free time for Tuesday
                continue;
            }

            $employeeFreetime = new EmployeeFreetime([
                'date' => $dayObj->toDateString(),
                'start_at' => '12:00:00',
                'end_at' => with(clone $dayObj)->hour(13)->addMinutes(15 * $i)->toTimeString(),
            ]);
            $employeeFreetime->user()->associate($this->user);
            $employeeFreetime->employee()->associate($employee);
            $employeeFreetime->saveOrFail();

            $employeeFreetimes[] = $employeeFreetime;
        }

        return $employeeFreetimes;
    }

    protected function _createCategoryServiceAndExtra($categoryCount = 1, $serviceCount = 2, $extraServiceCount = 0, Employee $employee = null)
    {
        static $categoryCreated = 0;
        static $serviceCreated = 0;
        static $extraServiceCreated = 0;

        if (empty($this->user)) {
            $this->_createUser(true);
        }

        if (empty($employee) && empty($this->employees)) {
            $this->_createEmployee();
        }

        $categories = [];

        for ($i = 0; $i < $categoryCount; $i++) {
            $category = new ServiceCategory([
                'name' => 'Category ' . (++$categoryCreated),
                'is_show_front' => 1,
            ]);
            $category->user()->associate($this->user);
            $category->saveOrFail();
            $categories[] = $category;

            for ($j = 0; $j < $serviceCount; $j++) {
                $service = new Service([
                    'name' => 'Service ' . (++$serviceCreated),
                    'during' => 15 * $serviceCreated,
                    'price' => 10 * $serviceCreated,
                    'is_active' => 1,
                ]);
                $service->setLength();
                $service->user()->associate($this->user);
                $service->category()->associate($category);
                $service->saveOrFail();

                if ($employee !== null) {
                    // attach with one employee
                    $service->employees()->attach($employee);
                } else {
                    // attach with all created employee
                    foreach ($this->employees as $employee) {
                        $service->employees()->attach($employee);
                    }
                }

                for ($k = 0; $k < $extraServiceCount; $k++) {
                    $extraService = new ExtraService([
                        'name' => 'Extra Service ' . (++$extraServiceCreated),
                        'price' => 10,
                        'length' => 15,
                    ]);
                    $extraService->user()->associate($this->user);
                    $extraService->saveOrFail();
                    $service->extraServices()->attach($extraService);
                }
            }
        }

        return $categories;
    }

    protected function _createConsumer(User $user = null)
    {
        static $i = 0;

        $consumer = new Consumer([
            'first_name' => sprintf('First_%d %d', $i, time()),
            'last_name' => 'Last',
            'email' => sprintf('consumer_%d_%d@varaa.com', $i, time()),
            'phone' => time(),
            'hash' => '',
        ]);
        $consumer->saveOrFail();

        if ($user != null) {
            $user->consumers()->attach($consumer->id);
        }

        $i++;
        return $consumer;
    }

    protected function _createConsumerGroup(User $user, $consumersCount = 2)
    {
        static $i = 0;

        $group = new Group([
            'name' => sprintf('Consumer Group %d', $i),
        ]);
        $group->user()->associate($user);
        $group->saveOrFail();

        for ($j = 0; $j < $consumersCount; $j++) {
            $consumer = $this->_createConsumer($user);
            $group->consumers()->attach($consumer->id);
        }

        $i++;
        return $group;
    }

    protected function _createCampaign(User $user)
    {
        $campaign = new Campaign([
            'subject' => 'Campaign Subject',
            'content' => 'Campaign Content',
            'from_email' => 'campaign@varaa.com',
            'from_name' => 'Varaa',
        ]);
        $campaign->user()->associate($user);
        $campaign->saveOrFail();

        return $campaign;
    }

    protected function _modelsReset()
    {
        // should be the first thing to be called in _before() method
        $this->user = null;
        $this->employees = array();

        // hacky way to workaround https://github.com/laravel/framework/issues/1181
        User::boot();
    }
}
