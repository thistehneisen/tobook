<?php namespace Test\Unit\Consumers\Models;

use App\Consumers\Models\Consumer;
use App\Core\Models\User;
use App\MarketingTool\Models\ConsumerUser;
use UnitTester;
use Watson\Validating\ValidationException;

/**
 * @group co
 */
class ConsumerCest
{
    public function testImportCsvSuccess(UnitTester $I)
    {
        $firstName = 'First';
        $lastName = 'Last';
        $email = 'consumer' . time() . '@varaa.com';
        $phone = time();
        $address = 'Address ' . time();
        $city = 'Helsinki';
        $postcode = '12345';
        $country = 'Finland';
        $maxId = \DB::table('consumers')->max('id');

        $lines = [];
        $lines[] = 'first_name,last_name,email,phone,address,city,postcode,country';
        $lines[] = sprintf('%s,%s,%s,%s,%s,%s,%s,%s', $firstName, $lastName, $email, $phone, $address, $city, $postcode, $country);

        $results = Consumer::importCsv($lines);

        $I->assertEquals(1, count($results), 'number of results');

        $result = reset($results);
        $I->assertTrue($result['success'], 'import has succeeded');

        $consumer = Consumer::where('id', '>', $maxId)->first();
        $I->assertEquals($firstName, $consumer->first_name, 'first_name');
        $I->assertEquals($lastName, $consumer->last_name, 'last_name');
        $I->assertEquals($email, $consumer->email, 'email');
        $I->assertEquals($phone, $consumer->phone, 'phone');
        $I->assertEquals($address, $consumer->address, 'address');
        $I->assertEquals($city, $consumer->city, 'city');
        $I->assertEquals($postcode, $consumer->postcode, 'postcode');
        $I->assertEquals($country, $consumer->country, 'country');

        $I->assertEquals(0, $consumer->users()->count(), 'no users');
    }

    public function testImportCsvAttachUser(UnitTester $I)
    {
        $user = User::find(70);

        $firstName = 'First';
        $lastName = 'Last';
        $email = 'consumer' . time() . '@varaa.com';
        $phone = time();
        $address = 'Address ' . time();
        $city = 'Helsinki';
        $postcode = '12345';
        $country = 'Finland';
        $maxId = \DB::table('consumers')->max('id');

        $lines = [];
        $lines[] = 'first_name,last_name,email,phone,address,city,postcode,country';
        $lines[] = sprintf('%s,%s,%s,%s,%s,%s,%s,%s', $firstName, $lastName, $email, $phone, $address, $city, $postcode, $country);

        $results = Consumer::importCsv($lines, $user);

        $I->assertEquals(1, count($results), 'number of results');

        $result = reset($results);
        $I->assertTrue($result['success'], 'import has succeeded');

        $consumer = Consumer::where('id', '>', $maxId)->first();
        $I->assertEquals($firstName, $consumer->first_name, 'first_name');
        $I->assertEquals($lastName, $consumer->last_name, 'last_name');
        $I->assertEquals($email, $consumer->email, 'email');
        $I->assertEquals($phone, $consumer->phone, 'phone');
        $I->assertEquals($address, $consumer->address, 'address');
        $I->assertEquals($city, $consumer->city, 'city');
        $I->assertEquals($postcode, $consumer->postcode, 'postcode');
        $I->assertEquals($country, $consumer->country, 'country');

        $I->assertEquals(1, $consumer->users()->count(), '1 user');
        $I->assertEquals($user->id, $consumer->users()->first()->id, 'user_id');

        $I->assertEquals(1, ConsumerUser::where('consumer_id', $consumer->id)->where('user_id', $user->id)->first()->is_visible, 'is_visible');
    }

    public function testImportErrorNoHeader(UnitTester $I)
    {
        $lines = [];

        $e = null;
        try {
            Consumer::importCsv($lines);
        } catch (ValidationException $ve) {
            $e = $ve;
        }

        $I->assertNotEmpty($e, 'exception');
        $I->assertEquals(trans('co.import.csv_header_is_missing'), $e->getMessage(), 'exception');
    }

    public function testImportErrorNoEmail(UnitTester $I)
    {
        $lines = [];
        $lines[] = 'first_name';
        $lines[] = 'First';

        $e = null;
        try {
            Consumer::importCsv($lines);
        } catch (ValidationException $ve) {
            $e = $ve;
        }

        $I->assertNotEmpty($e, 'exception');
        $I->assertEquals(trans('co.import.csv_required_field_x_is_missing', ['field' => 'email']), $e->getMessage(), 'exception');
    }
}
