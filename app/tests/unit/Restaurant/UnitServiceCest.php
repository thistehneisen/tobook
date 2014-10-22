<?php namespace Restaurant\Models;

use \UnitTester;
use App\Restaurant\Models\Service;
use App\Core\Models\User;

class UnitServiceCest
{
    public function testAddService(UnitTester $I)
    {
        $service = new Service;
        $service->fill([
            'name'      => 'Service 1',
            'start_at'  => '05:00:00',
            'end_at'    => '09:00:00',
            'length'    => 2,
            'price'     => 50,
        ]);
        $service->user()->associate(User::find(70));
        $service->save();

        $I->assertEquals($service->name, 'Service 1');
        $I->assertEquals($service->end_at, '09:00:00');
    }
}
