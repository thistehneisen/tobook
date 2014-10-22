<?php namespace Test\Functional\Restaurant;

use Test\Functional\Base;

class TableCest extends Base
{
    /**
     * @before login
     */

    public function seeAllPages(\FunctionalTester $I)
    {
        $I->amOnPage('/restaurant-booking/tables');
        $I->seeResponseCodeIs(200);
        $I->amOnPage('/restaurant-booking/tables/upsert');
        $I->seeResponseCodeIs(200);
    }
}
