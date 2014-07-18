<?php
use \AcceptanceTester;

class FlatPagesCest {
    public function testHome(AcceptanceTester $I) {
        $I->wantTo('see homepage');
        $I->amOnPage('/');
        $I->see('ETUSIVU');
        $I->see('REKISTERÖIDY');
        $I->see('KIRJAUDU');
    }
}