<?php
use \AcceptanceTester;
use Test\Elements\Auth;
use Test\Elements\Business;

/**
 * @group core
 */
class BusinessCest
{
    public function seeDashboard(AcceptanceTester $I)
    {
        $I->seeInCurrentUrl(Business::$dashboardUrl);
    }

    public function changePasswordWithoutAnyInformation(AcceptanceTester $I)
    {
        $I->amOnPage(Business::$changeProfileUrl);
        $I->submitForm(Business::$changeProfileForm, []);
        $I->seeElement(Business::$changeProfileForm.' .has-error');
    }

    public function changePasswordWithWrongOldPassword(AcceptanceTester $I)
    {
        $I->amOnPage(Business::$changeProfileUrl);
        $I->submitForm(Business::$changeProfileForm, [
            'old_password'          => 'matkhaucu',
            'password'              => 'daylamatkhau',
            'password_confirmation' => 'daylamatkhau',
        ]);
        $I->seeElement('.alert.alert-danger');
    }
}
