<?php namespace Test\Unit\Core\Models;

use App\Core\Models\User;
use UnitTester;
use Mockery as m;

/**
 * @group core
 */
class UserCest
{
    public function _after()
    {
        m::close();
    }

    public function testGetSearchDocumentOfBusinessUser(UnitTester $i)
    {
        $email = 'foo@bar.dev';

        $business = m::mock('App\Core\Models\Business')
            ->shouldReceive('getSearchDocument')->once()->andReturn([])
            ->getMock();

        $user = m::mock('App\Core\Models\User[getIsBusinessAttribute]');
        $user->shouldReceive('getIsBusinessAttribute')->once()->andReturn(true);
        $user->email = $email;
        $user->business = $business;

        $doc = $user->getSearchDocument();
        $i->assertTrue(!empty($doc));
        $i->assertEquals($doc['email'], $email);
        $i->assertTrue(array_key_exists('business', $doc));
    }

    public function testGetSearchDocumentOfConsumerUser(UnitTester $i)
    {
        $email = 'foo@bar.dev';

        $user = m::mock('App\Core\Models\User[getIsConsumerAttribute,getIsBusinessAttribute]');
        $user->shouldReceive('getIsBusinessAttribute')->andReturn(false);
        $user->shouldReceive('getIsConsumerAttribute')->andReturn(true);
        $user->email = $email;

        $consumer = m::mock('App\Consumers\Models\Consumer')
            ->shouldReceive('getSearchDocument')->once()->andReturn([])
            ->getMock();
        $user->consumer = $consumer;

        $doc = $user->getSearchDocument();
        $i->assertTrue(!empty($doc));
        $i->assertEquals($doc['email'], $email);
        $i->assertTrue(array_key_exists('consumer', $doc));
    }
}
