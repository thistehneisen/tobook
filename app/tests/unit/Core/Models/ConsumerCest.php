<?php
use App\Core\Models\Consumer;

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
