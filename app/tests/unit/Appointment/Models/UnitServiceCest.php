<?php
namespace Appointment\Models;
use App\Core\Models\User;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use \UnitTester;
use DB;

class UnitServiceCest
{
    public function testServiceLength(UnitTester $t)
    {
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
    }
}
