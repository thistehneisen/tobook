<?php namespace Test\Functional\FlashDeal;

use FunctionalTester;

/**
 * @group fd
 */
class FlashDealCest extends \Test\Functional\Base
{
    public function seeAllFdCrudPages(FunctionalTester $I)
    {
        foreach ([
            'services', 'coupons', 'flash_deals'
        ] as $route) {
            $I->amOnPage(route('fd.'.$route.'.index'));
            $I->seeResponseCodeIs(200);

            $I->amOnPage(route('fd.'.$route.'.upsert'));
            $I->seeResponseCodeIs(200);
        }
    }

    public function seeAllFdIndexPages(FunctionalTester $I)
    {
        $I->amOnPage(route('fd.index'));
        $I->seeResponseCodeIs(200);

        // see other tabs as well
        foreach ([
            'sold-flash-deals', 'sold-coupons', 'active-flash-deals',
            'active-coupons', 'expired-flash-deals', 'expired-coupons'
        ] as $tab) {
            $I->amOnPage(route('fd.index').'/'.$tab);
            $I->seeResponseCodeIs(200);
        }
    }
}
