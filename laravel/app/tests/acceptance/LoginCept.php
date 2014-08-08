<?php 
$i = new AcceptanceTester($scenario);
$i->wantTo('login to the site');
$i->amOnPage('/login');
$i->click('#btn-login');
$i->see('Errors!');
