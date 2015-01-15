<?php namespace Test\Api\Appointment\Controllers;

use \ApiTester;
use App\Core\Models\User;
use Test\Traits\Models;

/**
 * @group as
 */
class AuthCest
{
    use Models;

    public function testAccessDenied(ApiTester $I)
    {
        \Route::enableFilters();

        $I->sendGET('/api/v1.0/as/schedules');
        $I->seeResponseCodeIs(401);
    }

    public function testAccessGranted(ApiTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();

        \Route::enableFilters();

        $I->amHttpAuthenticated($this->user->email, 123456);
        $I->sendGET('/api/v1.0/as/schedules');
        $I->seeResponseCodeIs(200);
    }

    public function testUsername(ApiTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();

        \Route::enableFilters();

        $I->amHttpAuthenticated($this->user->username, 123456);
        $I->sendGET('/api/v1.0/as/schedules');
        $I->seeResponseCodeIs(200);
    }

    public function testOldPassword(ApiTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();

        \Route::enableFilters();

        $I->amHttpAuthenticated($this->user->email, 654321);
        $I->sendGET('/api/v1.0/as/schedules');
        $I->seeResponseCodeIs(200);
    }
}
