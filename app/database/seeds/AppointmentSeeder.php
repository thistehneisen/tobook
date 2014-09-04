<?php
use Illuminate\Support\Collection;
use App\Core\Models\User;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Resource;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Service;
use App\Appointment\Models\Employee;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(34);

        $items = [
            ['name' => 'Test Category 1', 'is_show_front' => 1],
            ['name' => 'Test Category 2', 'is_show_front' => 0],
            ['name' => 'Test Category 3', 'is_show_front' => 1],
        ];

        $categories = new Collection;
        foreach ($items as $item) {
            $category = new ServiceCategory($item);
            $category->user()->associate($user);
            $category->save();
            $categories->push($category);
        }

        $items = [
            ['name' => 'Resource 1', 'quantity' => mt_rand(1, 999)],
            ['name' => 'Resource 2', 'quantity' => mt_rand(1, 999)],
            ['name' => 'Resource 3', 'quantity' => mt_rand(1, 999)],
        ];
        $resouces = new Collection;
        foreach ($items as $item) {
            $resource = new Resource($item);
            $resource->user()->associate($user);
            $resource->save();
            $resouces->push($resource);
        }

        $items = [
            ['name' => 'Extra 1', 'price' => mt_rand(1, 999), 'length' => range(0, 60, 5)[mt_rand(0, 11)]],
            ['name' => 'Extra 2', 'price' => mt_rand(1, 999), 'length' => range(0, 60, 5)[mt_rand(0, 11)]],
            ['name' => 'Extra 3', 'price' => mt_rand(1, 999), 'length' => range(0, 60, 5)[mt_rand(0, 11)]],
        ];
        $extras = new Collection;
        foreach ($items as $item) {
            $extra = new ExtraService($item);
            $extra->user()->associate($user);
            $extra->save();
            $extras->push($extra);
        }

        $items = [
            [
                'name'        => 'Service 1',
                'price'       => mt_rand(0, 999),
                'length'      => range(0, 60, 5)[mt_rand(0, 11)],
                'before'      => range(0, 60, 5)[mt_rand(0, 11)],
                'during'      => range(0, 60, 5)[mt_rand(0, 11)],
                'after'       => range(0, 60, 5)[mt_rand(0, 11)],
                'is_active'   => 1
            ],
            [
                'name'        => 'Service 2',
                'price'       => mt_rand(0, 999),
                'length'      => range(0, 60, 5)[mt_rand(0, 11)],
                'before'      => range(0, 60, 5)[mt_rand(0, 11)],
                'during'      => range(0, 60, 5)[mt_rand(0, 11)],
                'after'       => range(0, 60, 5)[mt_rand(0, 11)],
                'is_active'   => 0
            ],
            [
                'name'        => 'Service 3',
                'price'       => mt_rand(0, 999),
                'length'      => range(0, 60, 5)[mt_rand(0, 11)],
                'before'      => range(0, 60, 5)[mt_rand(0, 11)],
                'during'      => range(0, 60, 5)[mt_rand(0, 11)],
                'after'       => range(0, 60, 5)[mt_rand(0, 11)],
                'is_active'   => 1
            ],
        ];

        $services = new Collection;
        foreach ($items as $item) {
            $service = new Service($item);
            $service->user()->associate($user);
            $service->category()->associate($categories->random());
            $service->save();

            $services->push($service);
        }

        // Employees
        $items = [
            [
                'name'                => 'Employee 1',
                'email'               => 'employee1@varra.com',
                'phone'               => '123',
                'is_subscribed_email' => 1,
                'is_subscribed_sms'   => 1,
                'is_active'           => 1
            ],
            [
                'name'                => 'Employee 2',
                'email'               => 'employee2@varra.com',
                'phone'               => '123',
                'is_subscribed_email' => 1,
                'is_subscribed_sms'   => 0,
                'is_active'           => 1
            ],
            [
                'name'                => 'Employee 3',
                'email'               => 'employee3@varra.com',
                'phone'               => '123',
                'is_subscribed_email' => 1,
                'is_subscribed_sms'   => 1,
                'is_active'           => 0
            ],
            [
                'name'                => 'Employee 4',
                'email'               => 'employee4@varra.com',
                'phone'               => '123',
                'is_subscribed_email' => 0,
                'is_subscribed_sms'   => 1,
                'is_active'           => 1
            ],
        ];

        $employees = new Collection;
        foreach ($items as $item) {
            $employee = new Employee($item);
            $employee->user()->associate($user);
            $employee->save();
            $employees->push($employee);

            // Remove all keys first, for safety
            $employee->services()->detach();
            $employee->services()->sync($services->lists('id'));
        }

        echo 'Done';
    }

}
