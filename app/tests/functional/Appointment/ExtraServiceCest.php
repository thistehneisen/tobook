<?php namespace Test\Functional\Appointment;

use Test\Functional\Base;

class ExtraServiceCest extends Base
{
    const INDEX  = '/appointment-scheduler/services/extras';
    const UPSERT = '/appointment-scheduler/services/extras/upsert';
    /**
     * @before login
     */
    public function seePages($i)
    {
        $i->amOnPage(self::INDEX);
        $i->seeResponseCodeIs(200);
        $i->amOnPage(self::UPSERT);
        $i->seeResponseCodeIs(200);
    }

    /**
     * @before login
     */
    public function addNewExtraServices($i)
    {
        $i->amOnPage(self::UPSERT);
        $i->fillField (['name' => 'name'], 'Foo');
        $i->fillField (['name' => 'price'], 199);
        $i->fillField (['name' => 'length'], 15);
        $i->fillField (['name' => '_token'], csrf_token());
        $i->click('#form-olut-upsert button[type=submit]');

        $i->haveRecord('as_extra_services', ['name' => 'Foo']);
    }
}
