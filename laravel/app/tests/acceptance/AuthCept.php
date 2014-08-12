<?php 
use Test\Elements\Auth;

$i = new AcceptanceTester($scenario);
//------------------------------------------------------------------------------
// Show validation errors
//------------------------------------------------------------------------------
$i->wantTo('see errors if I do not enter form');
$i->amOnPage(Auth::$loginUrl);
$i->submitForm(Auth::$loginForm, []);
$i->seeElement(Auth::$loginForm.' .has-error');

//------------------------------------------------------------------------------
// Show error message if providing wrong account information
//------------------------------------------------------------------------------
$i->wantTo('see errors if I provide wrong information');
$i->amOnPage(Auth::$loginUrl);
$i->submitForm(Auth::$loginForm, [
    'username' => 'foo',
    'password' => 'bar',
]);
$i->seeElement('.alert.alert-danger');

//------------------------------------------------------------------------------
// There is link to Registration page
//------------------------------------------------------------------------------
$i->wantTo('see link to Registration page');
$i->amOnPage(Auth::$loginUrl);
$i->click(Auth::$loginRegisterLink);
$i->seeInCurrentUrl(Auth::$registerUrl);

//------------------------------------------------------------------------------
// There is link to Forgot Password page
//------------------------------------------------------------------------------
$i->wantTo('see link to Forgot Password page');
$i->amOnPage(Auth::$loginUrl);
$i->click(Auth::$loginForgotLink);
$i->seeInCurrentUrl(Auth::$forgotPasswordUrl);

//------------------------------------------------------------------------------
// Show validation errors in registration page
//------------------------------------------------------------------------------
$i->wantTo('see validation errors if I submit empty form');
$i->amOnPage(Auth::$registerUrl);
$i->submitForm(Auth::$registerForm, []);
$i->seeElement(Auth::$registerForm.' .has-error');

//------------------------------------------------------------------------------
// There is link to Login page
//------------------------------------------------------------------------------
$i->wantTo('see link to login in registration page');
$i->amOnPage(Auth::$registerUrl);
$i->click(Auth::$registerLinkLogin);
$i->seeInCurrentUrl(Auth::$loginUrl);
