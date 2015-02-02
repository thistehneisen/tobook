<?php namespace Test\Functional\Admin;

use FunctionalTester;
use App\Core\Models\Business;
use App\Core\Models\User;

/**
 * @group core
 */
class UserCest extends \Test\Functional\Base
{
    protected $editUrl;
    protected $indexUrl;
    protected $stub;

    public function _before(FunctionalTester $i)
    {
        parent::_before($i);
        $this->createUrl = route('admin.users.upsert');
        $this->editUrl = route('admin.users.upsert', ['id' => $this->user->id]);
        $this->indexUrl = route('admin.users.index');
        $this->stub = [
            'note'             => 'Lorem',
            'name'             => 'Foo',
            'phone'            => '123 456 789',
            'meta_title'       => 'Meta title',
            'meta_keywords'    => 'Meta keywords',
            'meta_description' => 'Meta description',
            'bank_account'     => 'FI12 345 67890'
        ];
    }

    public function seePageToEditBusiness(FunctionalTester $i)
    {
        $i->amOnPage($this->editUrl);
        $i->seeResponseCodeIs(200);
    }

    public function editBusinessInformation(FunctionalTester $i)
    {
        $i->amOnPage($this->editUrl);
        $i->submitForm('#business-form', $this->stub + ['_token' => csrf_token()]);

        $business = Business::find($this->user->business->id);
        foreach ($this->stub as $field => $value) {
            $i->assertEquals($business->$field, $value);
        }
    }

    public function seeBusinessNoteIndication(FunctionalTester $i)
    {
        $selector = sprintf('#row-%d .business-note', $this->user->id);

        $i->amOnPage($this->indexUrl);
        $i->seeElement($selector);
    }

    public function seeFieldsToEnterBusinessMetadata(FunctionalTester $i)
    {
        $i->amOnPage($this->editUrl);
        $i->seeElement('[name=meta_title]');
        $i->seeElement('[name=meta_keywords]');
        $i->seeElement('[name=meta_description]');
    }

    public function seeNoDuplicatedBusinessInformation(FunctionalTester $i)
    {
        $i->amOnPage($this->editUrl);
        $i->submitForm('#business-form', $this->stub + ['_token' => csrf_token()]);

        $business = Business::ofUser($this->user->id);
        $i->assertTrue($business->count() === 1, 'no duplicated business');
    }

    public function createNewBusiness(FunctionalTester $i)
    {
        // Work-around to trigger model events in test environment
        User::boot();

        $email = uniqid().'@varaa.com';

        $i->amOnPage($this->createUrl);
        $i->submitForm('#lomake-form', [
            'email'                 => $email,
            'password'              => '1234567890',
            'password_confirmation' => '1234567890',
            'business_name'         => 'foo',
            'business_phone'        => 'bar',
        ]);

        $user = User::where('email', $email)->with('business')->first();
        $business = $user->business;
        $i->assertTrue($business->id !== null, 'Business exists');
        $i->assertEquals($business->name, 'foo', 'Business name');
        $i->assertEquals($business->phone, 'bar', 'Business phone');
    }

    public function seeErrorIfDuplicatingEmailBusiness(FunctionalTester $i)
    {
        $email = 'varaa_test@varaa.com';

        $i->amOnPage($this->createUrl);
        $i->submitForm('#lomake-form', [
            'email'                 => $email,
            'password'              => '1234567890',
            'password_confirmation' => '1234567890',
        ]);

        $user = User::where('email', $email)->get()->first();
        $i->assertTrue($user != null);
    }
}
