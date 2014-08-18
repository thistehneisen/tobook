<?php
use \AcceptanceTester;
use Test\Elements\Auth;

class AppointmentSchedulerCest {
    public function _before(AcceptanceTester $I) {
        $I->amOnPage(Auth::$loginUrl);
        $I->wantTo('Sign in');
        $I->submitForm(Auth::$loginForm, [
            'username' => 'capis',
            'password' => '123456',
        ]);
        $I->seeInCurrentUrl('dashboard');
        $I->amOnPage('/services/appointment-scheduler');
    }

    public function _after() {
    }

    // tests
    public function tryToTest(AcceptanceTester $I) {

    }

    public function testTabs(AcceptanceTester $I) {
        //$I->amOnPage('/appointment/index.php?username=capis&owner_id=1');
        $I->seeLink('Kalenteri');
    }
}
