<?php namespace Appointment\Models;

use App\Core\Models\User;
use App\Core\Models\Business;
use Appointment\Traits\Models;
use \UnitTester;

/**
 * @group core
 */
class UnitBusinessCest
{
    use Models;

    public function _before(UnitTester $I)
    {
        $this->_modelsReset();
        $this->_createUser(false);
    }

    public function testNewRecord(UnitTester $I)
    {
        $input = $this->getRequiredFieldsAndValues();

        // create business record
        $business = new Business($input);
        $business->user()->associate($this->user);
        $business->save();
        $I->assertNotNull($business->id, '$business->id');
        $I->assertTrue(empty($business->is_activated), '$business->is_activated');
        foreach ($input as $key => $value) {
            $I->assertEquals($business->$key, $value, '$business->' . $key);
        }

        foreach ($input as $key => $value) {
            $inputMissing = $input;
            unset($inputMissing[$key]);

            // unable to create business record (missing required field)
            $businessMissing = new Business($inputMissing);
            $businessMissing->user()->associate($this->user);
            $businessMissing->save();
            $I->assertNull($businessMissing->id, '$businessMissing' . ucwords($key) . '->id');
        }

        // unable to create business record (missing user)
        $businessMissingUser = new Business($input);
        try {
            $businessMissingUser->save();
        } catch (\Exception $e) {
            // ignore
        }
        $I->assertNull($businessMissingUser->id, '$businessMissingUser->id');
    }

    public function testGetFullAddress(UnitTester $I)
    {
        $input = $this->getRequiredFieldsAndValues();

        $business = new Business($input);
        $business->user()->associate($this->user);
        $business->save();

        $fullAddress = sprintf('%s, %s %s, %s', $input['address'], $input['postcode'], $input['city'], $input['country']);
        $I->assertEquals($fullAddress, $business->full_address, '$business->full_address');
    }

    private function getRequiredFieldsAndValues()
    {
        return $input = [
            'name' => 'Name',
            'address' => 'Address',
            'city' => 'City',
            'postcode' => 10000,
            'country' => 'Finland',
            'phone' => '1234567890',
        ];
    }
}
