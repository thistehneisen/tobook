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
        //$I->amOnPage('http://varaal4.dev/appointment/index.php?username=capis&owner_id=1');
    }

    public function _after() {
    }

    // tests
    public function tryToTest(AcceptanceTester $I) {

    }

    public function testTabs(AcceptanceTester $I) {
        //$I->amOnPage("/appointment/index.php?controller=pjAdminServices&action=pjActionIndex&as_pf=");
        //$I->click('Lisää kategoria');
        //$I->see('Kalenteri');
        //$I->switchToIFrame("varaa-iframe");
    }

    public function TestRedirect(AcceptanceTester $I) {
        /*$I->amOnPage('/appointment/index.php?controller=pjAdmin&action=pjActionIndex&as_pf=');
        $I->see('Ajanvarauksen tarjoaa varaa.com');
        $I->amOnPage('/appointment/index.php?controller=pjAdminBookings&action=pjActionIndex&as_pf=');
        $I->see('Ajanvarauksen tarjoaa varaa.com');
        $I->amOnPage('/appointment/index.php?controller=pjAdminServices&action=pjActionIndex&as_pf=');
        $I->see('Ajanvarauksen tarjoaa varaa.com');
        $I->amOnPage('/appointment/index.php?controller=pjAdminEmployees&action=pjActionIndex&as_pf=');
        $I->see('Ajanvarauksen tarjoaa varaa.com');
        $I->amOnPage('/appointment/index.php?controller=pjAdminOptions&action=pjActionIndex&tab=1&as_pf=');
        $I->see('Ajanvarauksen tarjoaa varaa.com');
        $I->amOnPage('/appointment/index.php?controller=pjAdminReports&action=pjActionEmployees&as_pf=');
        $I->see('Ajanvarauksen tarjoaa varaa.com');
        $I->amOnPage('http://varaal4.dev/appointment/index.php?controller=pjAdminOptions&action=pjActionInstall&as_pf=');
        $I->see('Ajanvarauksen tarjoaa varaa.com');*/
        //$I->amOnPage('/appointment/index.php?controller=pjAdminOptions&action=pjActionPreview&as_pf=&owner_id=1');
        $I->amOnPage('/appointment/library/index.php?controller=pjAdmin&action=pjActionIndex&as_pf=&owner_id=1');
        $I->see('Ajanvarauksen tarjoaa varaa.com');
        $I->amOnPage('/appointment/library/index.php?controller=pjAdminOptions&action=pjActionPreview&as_pf=&owner_id=1');
        $I->see('Ajanvarauksen tarjoaa varaa.com');
    }
}
