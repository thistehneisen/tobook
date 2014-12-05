<?php namespace Test\Functional\Admin;

use FunctionalTester;
use App\Core\Models\Business;

/**
 * @group core
 */
class UserCest extends \Test\Functional\Base
{
    protected $editUrl;
    protected $indexUrl;

    public function _before(FunctionalTester $i)
    {
        parent::_before($i);
        $this->editUrl = route('admin.users.upsert', ['id' => $this->user->id]);
        $this->indexUrl = route('admin.users.index');
    }

    public function seePageToEditBusiness(FunctionalTester $i)
    {
        $i->amOnPage($this->editUrl);
        $i->seeResponseCodeIs(200);
    }

    public function editBusinessInformation(FunctionalTester $i)
    {
        $input = [
            'note'             => 'Lorem',
            'name'             => 'Foo',
            'phone'            => '123 456 789',
            'meta_title'       => 'Meta title',
            'meta_keywords'    => 'Meta keywords',
            'meta_description' => 'Meta description',
        ];

        $i->amOnPage($this->editUrl);
        $i->submitForm('#business-form', $input + ['_token' => csrf_token()]);

        $business = Business::find($this->user->business->id);
        foreach ($input as $field => $value) {
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
}
