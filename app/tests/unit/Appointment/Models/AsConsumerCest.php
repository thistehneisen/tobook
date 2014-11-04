<?php namespace Test\Appointment\Models;

use App\Appointment\Models\AsConsumer;
use App\Appointment\Models\Consumer;
use App\Core\Models\User;
use \UnitTester;

/**
 * @group as
 */
class AsConsumerCest
{
    public function testNewConsumer(UnitTester $I)
    {
        $user = User::find(70);
        $email = 'consumer' . time() . '@varaa.com';
        $firstName = 'First ' . time();
        $lastName = 'Last ' . time();
        $phone = time();
        $maxId = \DB::table('consumers')->max('id');

        $consumer = AsConsumer::handleConsumer([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'hash' => '',
        ], $user);

        $I->assertGreaterThan($maxId, $consumer->id, 'id');
        $I->assertEquals($email, $consumer->email, 'email');
        $I->assertEquals($firstName, $consumer->first_name, 'first_name');
        $I->assertEquals($lastName, $consumer->last_name, 'last_name');
        $I->assertEquals($phone, $consumer->phone, 'phone');
        $I->assertNotEmpty($consumer->users()->find($user->id), '$consumer <-> $user');
    }

    public function testUpdateConsumer(UnitTester $I)
    {
        $user = User::find(70);
        $email = 'consumer' . time() . '@varaa.com';
        $firstName = 'First ' . time();
        $lastName = 'Last ' . time();
        $phone = time();
        $emailNew = 'new_' . $email;

        $consumer = Consumer::make([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
        ], $user);

        $consumerNew = AsConsumer::handleConsumer([
            'email' => $emailNew,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'hash' => '',
        ], $user);

        $I->assertEquals($consumer->id, $consumerNew->id, 'id');
        $I->assertEquals($consumer->first_name, $consumerNew->first_name, 'first_name');
        $I->assertEquals($consumer->last_name, $consumerNew->last_name, 'last_name');
        $I->assertEquals($consumer->phone, $consumerNew->phone, 'phone');
        $I->assertNotEquals($consumer->email, $consumerNew->email, 'email');
        $I->assertEquals($emailNew, $consumerNew->email, 'email');
    }

    public function testFromHash(UnitTester $I)
    {
        $user = User::find(70);
        $consumer = AsConsumer::handleConsumer([
            'email' => 'consumer' . time() . '@varaa.com',
            'first_name' => 'First ' . time(),
            'last_name' => 'Last ' . time(),
            'phone' => time(),
            'hash' => $user->hash,
        ]);

        $I->assertNotEmpty($consumer->users()->find($user->id), '$consumer <-> $user');
    }
}
