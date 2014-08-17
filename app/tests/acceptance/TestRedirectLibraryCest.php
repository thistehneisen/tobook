<?php
use \AcceptanceTester;

class TestRedirectLibraryCest
{
    public function _before()
    {
    }

    public function _after()
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->wantTo('Go to booking page');
        $I->amOnPage('/appointment/library/index.php?controller=pjAdminOptions&action=pjActionPreview&as_pf=&owner_id=34');
        $I->see('Ajanvarauksen tarjoaa varaa.com');
    }
}
