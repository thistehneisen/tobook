<?php namespace Test\Functional\Auth;

use FunctionalTester;

/**
 * @group core
 */
class ConsumerAuthCest extends \Test\Functional\Base
{
    public function seeConsumerRegisterPage(FunctionalTester $i)
    {
        $i->amOnPage('consumer/register');
        $i->seeResponseCodeIs(200);
    }
}
