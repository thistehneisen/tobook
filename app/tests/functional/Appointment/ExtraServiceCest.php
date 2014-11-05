<?php namespace Test\Functional\Appointment;

use FunctionalTester;

/**
 * @group as
 */
class ExtraServiceCest extends \Test\Functional\Base
{
    public function seePages(FunctionalTester $I)
    {
        $I->amOnPage(route('as.services.extras.index'));
        $I->seeResponseCodeIs(200);
        $I->amOnPage(route('as.services.extras.upsert'));
        $I->seeResponseCodeIs(200);
    }

    public function addNewExtraServices(FunctionalTester $I)
    {
        $I->amOnPage(route('as.services.extras.upsert'));
        $I->fillField(['name' => 'name'], 'Foo');
        $I->fillField(['name' => 'price'], 199);
        $I->fillField(['name' => 'length'], 15);
        $I->click('button[type=submit]');

        $I->canSeeRecord('as_extra_services', ['name' => 'Foo']);
    }
}
