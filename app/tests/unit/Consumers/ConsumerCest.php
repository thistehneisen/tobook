<?php
use App\Consumers\Models\Consumer;

/**
 * @group co
 */
class ConsumerCest
{
    public function testGetName(UnitTester $t)
    {
        $consumer = new Consumer;
        $consumer->first_name = 'John';
        $consumer->last_name = 'Doe';

        $t->assertEquals($consumer->name, 'John Doe');
    }
}
