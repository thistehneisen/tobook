<?php

use App\Core\Models\User;
use \Appointment\Traits\Models;

/**
 * @group core
 */
class ProfileCest
{
    use Models;

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();

        $I->amLoggedAs($this->user);
        $I->seeAuthentication();
    }

    public function testEditGeneral(FunctionalTester $I)
    {
        $email = 'another@domain.com';

        $I->amOnRoute('user.profile');
        $I->seeInField('email', $this->user->email);
        $I->submitForm('#general-form', ['email' => $email]);

        $newUser = User::find($this->user->id);
        $I->assertEquals($newUser->email, $email);
    }

    public function testEditBusiness(FunctionalTester $I)
    {
        $I->amOnRoute('user.profile');

        $formParams = [];
        foreach (['name', 'address', 'city', 'postcode', 'country', 'phone'] as $field) {
            $I->seeInField($field, $this->user->business->$field);
            $formParams[$field] = $field;
        }

        $I->submitForm('#business-form', $formParams);

        $newUser = User::find($this->user->id);
        foreach ($formParams as $field => $value) {
            $I->assertEquals($value, $newUser->business->$field);
        }
    }

    public function testEditBusinessDescription(FunctionalTester $I)
    {
        $I->amOnRoute('user.profile');
        $I->seeInField('description_html', '');

        $html = '<div><p><strong><color>Bold</color></strong>'
            . '<em><span>Italic</span></em><ul><li>One</li><li>Two</li></ul>'
            . '<ol><li><a href="http://google.com">Link</a></li></ol></p></div>';
        $htmlFiltered = '<p><strong>Bold</strong><em>Italic</em><ul><li>One</li><li>Two</li></ul><ol><li><a href="http://google.com">Link</a></li></ol></p>';
        $I->submitForm('#business-form', ['description_html' => $html]);

        $newUser = User::find($this->user->id);
        $I->assertEquals($htmlFiltered, $newUser->business->description_html);
    }
}
