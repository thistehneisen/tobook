<?php
use \AcceptanceTester;
use Test\Elements\Auth;

class AuthCest
{
    public function loginWithoutAnyInformation(AcceptanceTester $i)
    {
        $i->amOnPage(Auth::$loginUrl);
        $i->submitForm(Auth::$loginForm, []);
        $i->seeElement(Auth::$loginForm.' .has-error');
    }

    public function loginWithWrongInformation(AcceptanceTester $i)
    {
        $i->amOnPage(Auth::$loginUrl);
        $i->submitForm(Auth::$loginForm, [
            'username' => 'foo',
            'password' => 'bar',
        ]);
        $i->seeElement('.alert.alert-danger');
    }

    public function seeRegistrationLinkInLoginPage(AcceptanceTester $i)
    {
        $i->amOnPage(Auth::$loginUrl);
        $i->click(Auth::$loginRegisterLink);
        $i->seeInCurrentUrl(Auth::$registerUrl);
    }

    public function seeForgotPasswordLinkInLoginPage(AcceptanceTester $i)
    {
        $i->amOnPage(Auth::$loginUrl);
        $i->click(Auth::$loginForgotLink);
        $i->seeInCurrentUrl(Auth::$forgotPasswordUrl);
    }

    public function registerWithoutAnyInformation(AcceptanceTester $i)
    {
        $i->amOnPage(Auth::$registerUrl);
        $i->submitForm(Auth::$registerForm, []);
        $i->seeElement(Auth::$registerForm.' .has-error');
    }

    public function seeLoginLinkInRegistrationPage(AcceptanceTester $i)
    {
        $i->amOnPage(Auth::$registerUrl);
        $i->click(Auth::$registerLinkLogin);
        $i->seeInCurrentUrl(Auth::$loginUrl);
    }
}
