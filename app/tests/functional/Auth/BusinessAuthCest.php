<?php namespace Test\Functional\Auth;

use Test\Elements\Auth;
use FunctionalTester;

/**
 * @group core
 */
class AuthCest extends \Test\Functional\Base
{
    public function loginWithoutAnyInformation(FunctionalTester $I)
    {
        $I->amOnPage(Auth::$loginUrl);
        $I->submitForm(Auth::$loginForm, []);
        $I->seeElement(Auth::$loginForm.' .has-error');
    }

    public function loginWithWrongInformation(FunctionalTester $I)
    {
        $I->amOnPage(Auth::$loginUrl);
        $I->submitForm(Auth::$loginForm, [
            'username' => 'foo',
            'password' => 'bar',
        ]);
        $I->seeElement('.alert.alert-danger');
    }

    public function seeRegistrationLinkInLoginPage(FunctionalTester $I)
    {
        $I->amOnPage(Auth::$loginUrl);
        $I->click(Auth::$loginRegisterLink);
        $I->seeInCurrentUrl(Auth::$businessRegisterUrl);
    }

    public function seeForgotPasswordLinkInLoginPage(FunctionalTester $I)
    {
        $I->amOnPage(Auth::$loginUrl);
        $I->click(Auth::$loginForgotLink);
        $I->seeInCurrentUrl(Auth::$forgotPasswordUrl);
    }

    public function registerWithoutAnyInformation(FunctionalTester $I)
    {
        $I->amOnPage(Auth::$businessRegisterUrl);
        $I->submitForm(Auth::$registerForm, []);
        $I->seeElement(Auth::$registerForm.' .has-error');
    }

    public function seeLoginLinkInRegistrationPage(FunctionalTester $I)
    {
        $I->amOnPage(Auth::$businessRegisterUrl);
        $I->click(Auth::$registerLinkLogin);
        $I->seeInCurrentUrl(Auth::$loginUrl);
    }
}
