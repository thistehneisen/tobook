<?php namespace Core\Models;

use App\Core\Models\BusinessCategory;
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

    public function testCategories(UnitTester $I)
    {
        $input = $this->getRequiredFieldsAndValues();

        $business = new Business($input);
        $business->user()->associate($this->user);
        $business->save();

        $categoryIds = BusinessCategory::all()->lists('id');
        $I->assertGreaterThan(0, count($categoryIds), 'all category ids');
        $business->updateBusinessCategories($categoryIds);

        $foundIds = [];
        foreach ($business->businessCategories as $category) {
            $foundIds[] = $category->id;
        }
        $I->assertEquals(count($categoryIds), count($foundIds), 'category ids');
    }

    public function testImage(UnitTester $I)
    {
        $categoryName = 'hairdresser';

        $input = $this->getRequiredFieldsAndValues();

        $business = new Business($input);
        $business->user()->associate($this->user);
        $business->save();

        $category = BusinessCategory::where('name', $categoryName)->first();
        $I->assertNotNull($category, '$category');
        $business->businessCategories()->attach($category);

        $image = $business->image;
        $I->assertNotNull($image, 'image');
        $I->assertGreaterThan(0, strlen($image), 'strlen($image)');
        $I->assertTrue(file_exists(public_path() . $image), 'image file exists');
    }

    private function getRequiredFieldsAndValues()
    {
        return $input = [
            'name' => 'Name',
            'size' => '1',
            'address' => 'Address',
            'city' => 'City',
            'postcode' => 10000,
            'country' => 'Finland',
            'phone' => '1234567890',
        ];
    }
}
