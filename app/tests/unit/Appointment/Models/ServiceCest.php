<?php namespace Test\Appointment\Models;

use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\User;
use \UnitTester;

/**
 * @group as
 */
class UnitServiceCest
{
    public function testServiceLength(UnitTester $t)
    {
        $service = new Service;
        $service->fill([
            'name' => 'foo',
            'description' => 'bar',
            'before'=> 15,
            'during'=> 30,
            'after' => 15,
        ]);
        $service->user()->associate(User::find(70));
        $service->category()->associate(ServiceCategory::find(105));
        $service->setLength();
        $service->save();

        $t->assertEquals($service->name, 'foo');
        $t->assertEquals($service->length, 60);
    }
}
