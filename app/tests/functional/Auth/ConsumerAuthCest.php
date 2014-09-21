<?php namespace Test\Functional\Auth;

use Test\Functional\Base;

class ConsumerAuthCest extends Base
{
    public function seeConsumerRegisterPage(\FunctionalTester $i)
    {
        $i->amOnPage('consumer/register');
        $i->seeResponseCodeIs(200);
    }
}
