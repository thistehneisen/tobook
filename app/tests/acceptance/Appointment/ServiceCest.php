<?php
use Test\Elements\Auth;
use Test\Elements\Appointment;

class ServiceCest
{
    protected function login(AcceptanceTester $I)
    {
        $I->amOnPage(Auth::$loginUrl);
        $I->submitForm(Auth::$loginForm, [
            'username' => 'varaa_test',
            'password' => 'varaa_test'
        ]);
    }

    /**
     * @before login
     */
    public function seeAllServicePages(AcceptanceTester $I)
    {
        $I->amOnPage(Appointment::$serviceUrl);
        $I->seeElement('table.table');
        $I->amOnPage(Appointment::$serviceCategoryUrl);
        $I->seeElement('table.table');
        $I->amOnPage(Appointment::$serviceResourceUrl);
        $I->seeElement('table.table');
        $I->amOnPage(Appointment::$serviceExtraServiceUrl);
        $I->seeElement('table.table');
    }
}
