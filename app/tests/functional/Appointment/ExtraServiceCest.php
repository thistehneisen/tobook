<?php namespace Test\Functional\Appointment;

use FunctionalTester;

/**
 * @group as
 */
class ExtraServiceCest extends \Test\Functional\Base
{
    /**
     * @before login
     */
    public function seePages(FunctionalTester $I)
    {
        $I->amOnPage(route('as.services.extras.index'));
        $I->seeResponseCodeIs(200);
        $I->amOnPage(route('as.services.extras.upsert'));
        $I->seeResponseCodeIs(200);
    }

    /**
     * @before login
     */
    public function addNewExtraServices(FunctionalTester $I)
    {
        $I->amOnPage(route('as.services.extras.upsert'));
        $I->fillField(['name' => 'name'], 'Foo');
        $I->fillField(['name' => 'price'], 199);
        $I->fillField(['name' => 'length'], 15);
        $I->fillField(['name' => '_token'], csrf_token());
        $I->click('button[type=submit]');

        $I->haveRecord('as_extra_services', ['name' => 'Foo']);
    }
}
