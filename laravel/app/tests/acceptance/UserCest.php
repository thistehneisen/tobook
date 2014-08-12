<?php
use \AcceptanceTester;
use Test\Elements\Auth;
use Test\Elements\User;

class UserCest
{

    protected function login(AcceptanceTester $I)
    {
        $I->amOnPage(Auth::$loginUrl);
        $I->submitForm(Auth::$loginForm, [
            'username' => 'mikaeltestaa',
            'password' => 'kauppatie8'
        ]);
        $I->seeInCurrentUrl(User::$dashboardUrl);
    }

    /**
     * @before login
     */
    public function tryToChangePassword(AcceptanceTester $I)
    {
        $I->wantTo('change password');
        $I->amOnPage(User::$changeProfileUrl);
        $I->submitForm(User::$changeProfileForm, []);
        $I->seeElement(User::$changeProfileForm.' .has-error');

        $I->submitForm(User::$changeProfileForm, [
            'old_password'          => 'matkhaucu',
            'password'              => 'daylamatkhau',
            'password_confirmation' => 'daylamatkhau',
        ]);
        $I->seeElement('.alert.alert-danger');
    }
}
