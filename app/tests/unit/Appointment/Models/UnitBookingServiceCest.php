<?php
namespace Appointment\Models;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Core\Models\User;
use App\Core\Models\Role;
use Carbon\Carbon;
use \UnitTester;
use Util;

class UnitBookingServiceCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function tryToTest(UnitTester $t)
    {

        $user                        = new User;
        $user->username              = 'eureka287asdadsd';
        $user->email                 = 'eureka287asdadsd@yahoo.com';
        $user->password              = '123asdasds';
        $user->password_confirmation = '123asdasds';

        // Now we need to check existing consumer
        $user->validateExistingConsumer();

        $user->save();

        // Assign the role
        // $role = Role::consumer();
        // $user->attachRole($role);

        $category = new ServiceCategory;
        $category->name = 'Category 1';
        $category->description = 'description';
        // $category->save();

        $service = new Service;
        $service->fill([
                'name' => 'Service 1',
                'description' => 'Service 1 Descritpion',
                'before'=> 15,
                'during'=> 30,
                'after' => 15,
                'user_id' => 133,
                'category_id'=>1
            ]);
        $service->setLength();
        $service->save();
        $uuid = Util::uuid();

        $employee = new Employee;
        $employee->fill([
            'name'          => 'Employee 1',
            'email'         => 'employee1@varaa.com',
            'phone'         => '0417559373',
            'description'   => 'Employee description',
        ]);
        $employee->user()->associate($user);
        $employee->save();

        $bookingService = new BookingService;
        $bookingService->fill([
            'tmp_uuid' => $uuid,
            'date' => Carbon::now(),
            'start_at' => '08:00',
            'end_at'   => '09:00',
            'modify_time' => 0
        ]);
        $bookingService->user()->associate($user);
        $bookingService->service()->associate($service);
        $bookingService->employee()->associate($employee);
        $bookingService->save();

        $t->assertEquals($bookingService->tmp_uuid, $uuid);
        $t->assertEquals($bookingService->getCartStartAt()->toTimeString(), '08:15:00');
        $t->assertEquals($bookingService->getCartEndAt()->toTimeString(), '08:45:00');
        $t->assertEquals($category->name, 'Category 1');
        $t->assertEquals($service->name, 'Service 1');
        $t->assertEquals($service->length, 60);
    }
}
