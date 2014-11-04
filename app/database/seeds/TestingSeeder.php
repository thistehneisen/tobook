<?php

use App\Appointment\Models\Employee;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Consumers\Models\Consumer;
use App\Core\Models\Business;
use App\Core\Models\BusinessCategory;
use App\Core\Models\Module;
use App\Core\Models\Permission;
use App\Core\Models\Role;
use App\Core\Models\User;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() !== 'testing') {
            throw new InvalidArgumentException("TestingSeeder should only be ran in testing environment.\nTry `php artisan db:seed --class=TestingSeeder --env=testing`");
        }

        $this->_core();
        $this->_as();
        $this->_modules();
    }

    private function _core()
    {
        $this->_truncate(Role::query());
        $this->_truncate(Permission::query());
        $this->call('EntrustSeeder');

        User::where('id', 70)->forceDelete();
        User::where('username', 'varaa_test')->forceDelete();
        User::where('email', 'varaa_test@varaa.com')->forceDelete();
        $this->user = new User([
            'username' => 'varaa_test',
            'email' => 'varaa_test@varaa.com',
        ]);
        $this->user->id = 70;
        $this->user->password = 'varaa_test';
        $this->user->password_confirmation = 'varaa_test';
        $this->user->save();
        $this->user->attachRole(Role::admin());

        $this->_truncate(BusinessCategory::withTrashed());
        $this->call('BusinessCategorySeeder');

        $this->business = new Business([
            'name' => 'Varaa Test',
            'size' => 1,
            'address' => 'Address',
            'city' => 'City',
            'postcode' => 10000,
            'country' => 'Finland',
            'phone' => '1234567890',
        ]);
        $this->business->is_activated = true;
        $this->business->user()->associate($this->user);
        $this->business->updateBusinessCategories(range(1, BusinessCategory::count()));
        $this->business->saveOrFail();

        Consumer::where('id', 1)->forceDelete();
        $consumer = new Consumer([
            'first_name' => 'First',
            'last_name' => 'Last',
            'email' => 'consumer@varaa.com',
            'phone' => '1234567890',
            'address' => 'Consumer Address',
            'city' => 'Consumer City',
            'postcode' => 10001,
            'country' => 'Finland',
        ]);
        $consumer->id = 1;
        $consumer->save();
        $this->user->consumers()->attach($consumer->id, ['is_visible' => true]);
    }

    private function _as()
    {
        Employee::where('id', 63)->forceDelete();
        $this->employee = new Employee([
            'name' => 'Employee',
            'email' => 'employee@varaa.com',
            'phone' => '1234567890',
            'is_active' => 1,
        ]);
        $this->employee->id = 63;
        $this->employee->user()->associate($this->user);
        $this->employee->saveOrFail();

        Employee::where('id', 64)->forceDelete();
        $employee2 = new Employee([
            'name' => 'Employee 2',
            'email' => 'employee2@varaa.com',
            'phone' => '1234567890',
            'is_active' => 1,
        ]);
        $employee2->id = 64;
        $employee2->user()->associate($this->user);
        $employee2->saveOrFail();

        ServiceCategory::where('id', 105)->forceDelete();
        $this->category = new ServiceCategory([
            'name' => 'Service Category',
            'is_show_front' => 1,
        ]);
        $this->category->id = 105;
        $this->category->user()->associate($this->user);
        $this->category->saveOrFail();

        Service::where('id', 301)->forceDelete();
        $this->service = new Service([
            'name' => 'Klassinen hieronta',
            'length' => 45,
            'during' => 30,
            'after' => 15,
            'price' => 35,
            'is_active' => 1,
        ]);
        $this->service->id = 301;
        $this->service->user()->associate($this->user);
        $this->service->category()->associate($this->category);
        $this->service->saveOrFail();
        $this->service->employees()->attach($this->employee);
        $this->service->employees()->attach($employee2, ['plustime' => 15]);

        Service::where('id', 302)->forceDelete();
        $service2 = new Service([
            'name' => 'Service 2',
            'length' => 45,
            'during' => 30,
            'before' => 15,
            'price' => 35,
            'is_active' => 1,
        ]);
        $service2->id = 302;
        $service2->user()->associate($this->user);
        $service2->category()->associate($this->category);
        $service2->saveOrFail();
        $service2->employees()->attach($this->employee);

        ExtraService::where('id', 1)->forceDelete();
        $extraService = new ExtraService([
            'name' => 'Extra Service',
            'price' => 10,
            'length' => 15,
        ]);
        $extraService->id = 1;
        $extraService->user()->associate($this->user);
        $extraService->saveOrFail();
        $this->service->extraServices()->attach($extraService);

        ServiceTime::where('id', 1)->forceDelete();
        $serviceTime = new ServiceTime([
            'price' => 50,
            'length' => 160,
            'before' => 30,
            'during' => 100,
            'after' => 30,
        ]);
        $serviceTime->id = 1;
        $serviceTime->service()->associate($this->service);
        $serviceTime->saveOrFail();
    }

    private function _modules() {
        $this->_truncate(Module::withTrashed());
        $this->call('ModuleSeeder');
    }

    private function _truncate($builder) {
        foreach ($builder->get() as $model) {
            $model->forceDelete();
        }
        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . $builder->getModel()->getTable() . ' AUTO_INCREMENT = 1');
    }

}
