<?php namespace Test\Consumers\Controllers;

use App\Core\Models\Role;
use Appointment\Traits\Models;
use FunctionalTester;
use Lang;

/**
 * @group co
 */
class HubCest
{
    use Models;

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();

        $this->_createUser();
        $this->user->attachRole(Role::admin());

        $I->amLoggedAs($this->user);
    }

    public function testSuccess(FunctionalTester $I)
    {
        $I->amOnRoute('consumer-hub.import');
        $I->attachFile('upload', 'Consumers/Controllers/Hub/success.csv');
        $I->click('#btn-import');

        $I->amOnRoute('consumer-hub.import');
        $I->see(Lang::choice('co.import.imported_x', 1));

        $count = $this->user->consumers()->count();
        $I->assertEquals(1, $count, 'number of consumers');
    }

    public function testSuccessTwo(FunctionalTester $I)
    {
        $I->amOnRoute('consumer-hub.import');
        $I->attachFile('upload', 'Consumers/Controllers/Hub/success2.csv');
        $I->click('#btn-import');

        $I->amOnRoute('consumer-hub.import');
        $I->see(Lang::choice('co.import.imported_x', 2, ['count' => 2]));

        $count = $this->user->consumers()->count();
        $I->assertEquals(2, $count, 'number of consumers');
    }

    public function testErrorNoUpload(FunctionalTester $I)
    {
        $I->amOnRoute('consumer-hub.import');
        $I->click('#btn-import');

        $I->amOnRoute('consumer-hub.import');
        $I->see(trans('co.import.upload_is_missing'));

        $count = $this->user->consumers()->count();
        $I->assertEquals(0, $count, 'number of consumers');
    }
}
