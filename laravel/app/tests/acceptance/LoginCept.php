<?php 
$i = new AcceptanceTester($scenario);
/**
 * Show validation errors
 */
$i->wantTo('see errors if I do not enter form');
$i->amOnPage('/login');
$i->submitForm('#frm-login', []);
$i->see('The username field is required.');
$i->see('The password field is required.');

/**
 * Show error message if providing wrong account information
 */
$i->wantTo('see errors if I provide wrong information');
$i->amOnPage('/login');
$i->submitForm('#frm-login', [
    'username' => 'foo',
    'password' => 'bar',
]);
$i->see('Incorrect username, email or password.');

