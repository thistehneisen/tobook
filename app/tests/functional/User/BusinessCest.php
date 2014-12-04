<?php
use App\Core\Models\User;
use Test\Traits\Models;
/**
 * @group core
 */
class BusinessCest
{
    use Models;

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();

        $I->amLoggedAs($this->user);
        $I->seeAuthentication();
    }

    public function seeModules(FunctionalTester $I)
    {
        $I->amOnRoute('dashboard.index');
        $I->seeElement('ul.dashboard-services');
        $I->see('Ajanvaraus', 'h4');
        $I->see('Asiakkaat', 'h4');
    }
}
