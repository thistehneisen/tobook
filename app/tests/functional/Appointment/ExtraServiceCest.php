<?php namespace Test\Functional\Appointment;

use Test\Functional\Base;

class ExtraServiceCest extends Base
{
    /**
     * @before login
     */
    public function seeIndexPage(\FunctionalTester $i)
    {
        $i->amOnPage('/appointment-scheduler/services/extras');
        $i->seeResponseCodeIs(200);
    }
}
