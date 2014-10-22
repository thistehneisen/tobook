<?php namespace Test\Functional\Restaurant;

use Test\Functional\Base;

class ServiceCest extends Base
{
    /**
     * @before login
     */

    public function seeAllPages(\FunctionalTester $I)
    {
        $I->amOnPage('/restaurant-booking/services');
        $I->seeResponseCodeIs(200);
        $I->amOnPage('/restaurant-booking/services/upsert');
        $I->seeResponseCodeIs(200);
    }
}
