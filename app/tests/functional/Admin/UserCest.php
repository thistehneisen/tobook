<?php namespace Test\Functional\Admin;

use FunctionalTester;

/**
 * @group core
 */
class UserCest extends \Test\Functional\Base
{
    protected $editUrl;

    public function _before(FunctionalTester $i)
    {
        parent::_before($i);
        $this->editUrl = route('admin.users.upsert', ['id' => $this->user->id]);
    }

    public function seePageToEditBusiness(FunctionalTester $i)
    {
        $i->amOnPage($this->editUrl);
        $i->seeResponseCodeIs(200);
    }

    public function editBusinessInformation(FunctionalTester $i)
    {
        $input = [
            'note'  => 'Lorem',
            'name'  => 'Foo',
            'phone' => '123 456 789'
        ];

        $i->amOnPage($this->editUrl);
        $i->click('a[href="#tab-business"]');
        $i->submitForm('#business-form', $input);

        foreach ($input as $field => $value) {
            $i->seeInField('[name='.$field.']', $value);
        }
    }
}
