<?php
use \AcceptanceTester;
use Test\Pages\Auth;

class UserCest
{
    public static $changeProfileUrl = '/profile';
    public static $changeProfileForm = '#frm-profile';

    public function _before()
    {
    }

    public function _after()
    {
    }

    protected function login(AcceptanceTester $I)
    {
        $I->amOnPage(Auth::$loginUrl);
        $I->submitForm(Auth::$loginForm, [
            'username' => 'mikaeltestaa',
            'password' => 'webzensux@'
        ]);
    }

    /**
     * @before login
     */
    public function tryToChangePassword(AcceptanceTester $I)
    {
        $I->wantTo('change password');
        $I->amOnPage(static::$changeProfileUrl);
        $I->submitForm(static::$changeProfileForm, []);
        $I->seeElement(static::$changeProfileForm.' .has-error');

        $I->submitForm(static::$changeProfileForm, [
            'old_password'          => 'matkhaucu',
            'password'              => 'daylamatkhau',
            'password_confirmation' => 'daylamatkhau',
        ]);
        $I->seeElement('.alert.alert-danger');
    }
}
