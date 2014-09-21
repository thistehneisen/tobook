<?php

class ConsumerAuthCest
{
    public function seeConsumerRegisterPage(FunctionalTester $i)
    {
        $i->amOnPage('consumer/register');
        $i->seeResponseCodeIs(200);
    }
}
