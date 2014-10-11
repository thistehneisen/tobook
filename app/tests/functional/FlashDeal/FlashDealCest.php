<?php namespace Test\Functional\FlashDeal;

use FunctionalTester;

/**
 * @group fd
 */
class FlashDealCest extends \Test\Functional\Base
{
    /**
     * @before login
     */
    public function seeAllFDPages(FunctionalTester $I)
    {
        $I->amOnPage('/flash-deal');
        $I->seeResponseCodeIs(200);
        $I->amOnPage('/flash-deal/services');
        $I->seeResponseCodeIs(200);
        $I->amOnPage('/flash-deal/coupons');
        $I->seeResponseCodeIs(200);
        $I->amOnPage('/flash-deal/flash-deals');
        $I->seeResponseCodeIs(200);
    }
}
