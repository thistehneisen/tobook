<?php

use App\Appointment\Models\Employee;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Service;
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

        User::where('id', 70)->delete();
        User::where('username', 'varaa_test')->delete();
        User::where('email', 'varaa_test@varaa.com')->delete();
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
    }

    private function _as()
    {
        Employee::where('id', 63)->delete();
        $this->employee = new Employee([
            'name' => 'Employee',
            'email' => 'employee@varaa.com',
            'phone' => '1234567890',
            'is_active' => 1,
        ]);
        $this->employee->id = 63;
        $this->employee->user()->associate($this->user);
        $this->employee->saveOrFail();

        ServiceCategory::where('id', 105)->delete();
        $this->category = new ServiceCategory([
            'name' => 'Service Category',
            'is_show_front' => 1,
        ]);
        $this->category->id = 105;
        $this->category->user()->associate($this->user);
        $this->category->saveOrFail();

        Service::where('id', 301)->delete();
        $this->service = new Service([
            'name' => 'Klassinen hieronta',
            'description' => '30min',
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
