<?php
namespace Appointment\Models;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\User;
use App\Core\Models\Role;

use \UnitTester;

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
                'name' => 'eureka287',
                'description' => 'eureka287@yahoo.com',
                'before'=> 15,
                'during'=> 30,
                'after' => 15,
                'user_id' => 133,
                'category_id'=>1
            ]);
        $service->setLength();
        $service->save();

        $t->assertEquals($category->name, 'Category 1');
        $t->assertEquals($service->name, 'eureka287');
        $t->assertEquals($service->length, 60);
    }
}
