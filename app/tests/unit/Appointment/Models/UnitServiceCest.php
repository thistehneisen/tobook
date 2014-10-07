<?php
namespace Appointment\Models;
use App\Core\Models\User;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use \UnitTester;
use DB;

class UnitServiceCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function tryToTest(UnitTester $I)
    {
    }

    public function testServiceLength(UnitTester $t){
        // $user = new User;
        // $user->unguard();
        // $user->fill([
        //         'username' => 'eureka287',
        //         'email' => 'eureka287@yahoo.com',
        //         'password' => 'salasana'
        //     ]);
        // $user->reguard();
        // $user->save();

        // $category = new ServiceCategory;
        // $category->id = 1;
        // $category->name = 'Category 1';
        // $category->description = 'description';
        // $category->user_id = $user->id;
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

        $t->assertEquals($service->name, 'eureka287');
        $t->assertEquals($service->length, 60);
        // $t->assertEquals($service->id, 23);
    }
}
