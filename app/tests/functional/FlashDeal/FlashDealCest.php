<?php
// codecept run functional FlashDeal/FlashDealCest.php
use App\Core\Models\User;

class FlashDealCest
{
    protected function login(FunctionalTester $I)
    {
        $user = new User([
            'username' => 'mikaeltestaa',
            'password' => 'kauppatie8'
        ]);
        $I->amLoggedAs($user);
    }

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
