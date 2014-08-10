<?php 
use Test\Pages\Auth;

$i = new AcceptanceTester($scenario);
/**
 * Show validation errors
 */
$i->wantTo('see errors if I do not enter form');
$i->amOnPage(Auth::$loginUrl);
$i->submitForm(Auth::$loginForm, []);
$i->seeElement(Auth::$loginForm.' .has-error');

/**
 * Show error message if providing wrong account information
 */
$i->wantTo('see errors if I provide wrong information');
$i->amOnPage(Auth::$loginUrl);
$i->submitForm(Auth::$loginForm, [
    'username' => 'foo',
    'password' => 'bar',
]);
$i->seeElement('.alert.alert-danger');

