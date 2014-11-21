<?php namespace Test\Functional\Core\Ajax;

use App\Core\Models\Business;
use Test\Traits\Models;

/**
 * @group core
 */
class SearchCest
{
    use Models;

    public function _before(\ApiTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();

        $I->amLoggedAs($this->user);
    }

    public function testGetLocations(\ApiTester $I)
    {
        $I->sendGET(route('ajax.locations'));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $response = $I->grabDataFromJsonResponse();
        $cities = [];
        foreach ($response as $responseCity) {
            $cities[] = $responseCity['name'];
        }

        foreach (Business::all() as $business) {
            $I->assertTrue(in_array($business->city, $cities), $business->city . ' in $cities');
        }
    }
}
