<?php namespace Test\Functional\Appointment;

use FunctionalTester;

/**
 * @group as
 */
class ServiceCest extends \Test\Functional\Base
{
    public function seePages(FunctionalTester $I)
    {
        $I->amOnPage(route('as.services.index'));
        $I->seeResponseCodeIs(200);
    }
}
