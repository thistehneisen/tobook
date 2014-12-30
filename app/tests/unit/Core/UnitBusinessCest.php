<?php namespace Test\Unit\Core\Models;

use App\Core\Models\BusinessCategory;
use App\Core\Models\User;
use App\Core\Models\Business;
use Test\Traits\Models;
use UnitTester;
use Mockery as m;

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

        Business::boot();
    }

    public function _after()
    {
        m::close();
    }

    public function testNewRecordSuccess(UnitTester $I)
    {
        $input = $this->getRequiredFieldsAndValues();
        $business = new Business($input);
        $business->user()->associate($this->user);
        $business->save();
        $I->assertNotNull($business->id, '$business->id');
        $I->assertTrue(empty($business->is_activated), '$business->is_activated');
        foreach ($input as $key => $value) {
            $I->assertEquals($business->$key, $value, '$business->' . $key);
        }
    }

    public function testNewRecordFailure(UnitTester $I)
    {
        $input = $this->getRequiredFieldsAndValues();
        foreach ($input as $key => $value) {
            $inputMissing = $input;
            unset($inputMissing[$key]);

            // unable to create business record (missing required field)
            $businessMissing = new Business($inputMissing);
            $businessMissing->user()->associate($this->user);
            $result = $businessMissing->saveOrReturn();
            $I->assertFalse($result, '$businessMissing' . ucwords($key) . '->id');
        }

        // unable to create business record (missing user)
        $businessMissingUser = new Business($input);
        try {
            $result = $businessMissingUser->saveOrReturn();
            $I->assertFalse($result, '$businessMissingUser->id');
        } catch (\Exception $e) {
            // ignore
        }
    }

    public function testGetFullAddress(UnitTester $I)
    {
        $input = [
            'address'   => 'abc',
            'postcode'  => '00100',
            'city'      => 'Helsinki',
            'country'   => 'Finland',
        ];

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
        $I->assertTrue(file_exists(public_path() . '/' . $image), 'image file exists');
    }

    public function testGetSlug(UnitTester $i)
    {
        $business = new Business(['name' => 'This is a name']);
        $i->assertEquals($business->slug, 'this-is-a-name');
    }

    private function getRequiredFieldsAndValues()
    {
        return $input = [
            'name' => 'Name',
            'phone' => '1234567890',
        ];
    }

    public function testGetTotalCommission(UnitTester $i)
    {
        $mock = m::mock('\App\Appointment\Models\Booking[calculateCommissions]');
        $mock->shouldReceive('calculateCommissions')->andReturn(10);

        \App::instance('\App\Appointment\Models\Booking', $mock);
        $business = new Business();
        $business->user()->associate($this->user);
        $i->assertEquals($business->total_commission, '10.00');
    }
}
