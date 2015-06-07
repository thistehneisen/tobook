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
class BusinessCest
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
        $business = new Business([
            'address'   => 'abc',
            'postcode'  => '00100',
            'city'      => 'Helsinki',
            'country'   => 'Finland',
        ]);

        $I->assertEquals($business->full_address, 'abc, 00100 Helsinki, Finland');

        $business->district = 'Punavuori';
        $I->assertEquals($business->full_address, 'abc, Punavuori, 00100 Helsinki, Finland');
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

    public function testGetPaidCommission(UnitTester $i)
    {
        $mock = m::mock('\App\Core\Models\CommissionLog[calculatePaid]');
        $mock->shouldReceive('calculatePaid')->andReturn(17.8);
        \App::instance('\App\Core\Models\CommissionLog', $mock);

        $business = new Business();
        $business->user()->associate($this->user);
        $i->assertEquals($business->paid_commission, '17.80');
    }

    public function testGetWorkingHoursArray(UnitTester $i)
    {
        $business = new Business();
        $working_hours = $business->working_hours_array;
        $i->assertTrue(is_array($working_hours), 'Working hours exists');
        foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day) {
            $i->assertTrue(isset($working_hours[$day]), $day.' exists');
        }
        $i->assertEquals($working_hours['fri']['start'], '08:00');
        $i->assertEquals($working_hours['fri']['end'], '20:00');
        $i->assertEquals($working_hours['fri']['extra'], '');
    }

    public function testSetWorkingHours(UnitTester $i)
    {
        $data = [
            'mon' => ['start' => '09:00', 'end' => '20:30', 'extra' => 'empty'],
        ];
        $business = new Business();
        $business->working_hours = $data;

        $i->assertEquals($business->working_hours,
            '{"mon":{"start":"09:00","end":"20:30","extra":"empty"}}');
    }
}
