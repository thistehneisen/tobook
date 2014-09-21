<?php
// codecept run functional FlashDeal/FlashDealCest.php
class FlashDealCest extends BaseCest
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
