<?php
namespace Appointment\Schedule;

use \ApiTester;

/**
 * @group as
 */
class AuthCest
{
    public function testAccessDenied(ApiTester $I)
    {
        \Illuminate\Support\Facades\Route::enableFilters();

        $I->sendGET('/api/v1.0/as/schedules');
        $I->seeResponseCodeIs(401);
    }
}
