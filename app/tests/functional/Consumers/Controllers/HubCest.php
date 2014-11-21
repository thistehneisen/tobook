<?php namespace Test\Consumers\Controllers;

use App\Appointment\Models\AsConsumer;
use App\Consumers\Models\Group;
use App\Core\Models\Role;
use FunctionalTester;
use Lang;
use Test\Traits\Booking;
use Test\Traits\Models;

/**
 * @group co
 */
class HubCest
{
    use Models;
    use Booking;

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();

        $this->_createUser();
        $this->user->attachRole(Role::admin());

        $I->amLoggedAs($this->user);
    }

    // TODO: crud tests

    public function testHistoryAS(FunctionalTester $I)
    {
        $categories = $this->_createCategoryServiceAndExtra(2);
        $date = $this->_getNextDate();

        $bookingSingle = $this->_book($this->user, $categories[1], $date);

        $bookings = [];
        for ($i = 0; $i < 5; $i++) {
            $bookings[] = $this->_book($this->user, $categories[0], $date->addDay());
        }

        $I->amOnRoute('consumer-hub.history', [
            'id' => $bookings[0]->consumer->id,
            'service' => 'as',
        ]);

        foreach ($bookings as $booking) {
            $I->see($booking->uuid);
            $I->see($booking->date);
            $I->see($booking->start_at);
            $I->see($booking->end_at);
            $I->see($booking->bookingServices()->first()->service->name);
            $I->see($booking->created_at);
        }

        // one row for header, one row for each booking
        $I->seeNumberOfElements('tr', 1 + count($bookings));

        // test with consumer with one booking
        $I->amOnRoute('consumer-hub.history', [
            'id' => $bookingSingle->consumer->id,
            'service' => 'as',
        ]);
        $I->see($bookingSingle->uuid);
        $I->see($bookingSingle->date);
        $I->see($bookingSingle->start_at);
        $I->see($bookingSingle->end_at);
        $I->see($bookingSingle->bookingServices()->first()->service->name);
        $I->see($bookingSingle->created_at);
        $I->seeNumberOfElements('tr', 2);

        // test with consumer with no booking
        $consumerNoBookings = AsConsumer::handleConsumer([
            'first_name' => 'First ' . time(),
            'last_name' => 'Last',
            'email' => 'consumer_' . time() . '@varaa.com',
            'phone' => time(),
            'hash' => '',
        ], $this->user);
        $I->amOnRoute('consumer-hub.history', [
            'id' => $consumerNoBookings->id,
            'service' => 'as',
        ]);
        $I->seeNumberOfElements('tr', 1);
    }

    public function testHistoryLC(FunctionalTester $I)
    {
        // TODO: tests with data

        $consumer = $this->_createConsumer($this->user);

        $lcConsumer = new \App\LoyaltyCard\Models\Consumer();
        $lcConsumer->consumer()->associate($consumer);
        $lcConsumer->user()->associate($this->user);
        $I->amOnRoute('consumer-hub.history', [
            'id' => $consumer->id,
            'service' => 'lc',
        ]);
        $I->seeNumberOfElements('tr', 1);
    }

    public function testImportSuccess(FunctionalTester $I)
    {
        $I->amOnRoute('consumer-hub.import');
        $I->attachFile('upload', 'Consumers/Controllers/Hub/success.csv');
        $I->click('#btn-import');

        $I->amOnRoute('consumer-hub.import');
        $I->see(Lang::choice('co.import.imported_x', 1));

        $count = $this->user->consumers()->count();
        $I->assertEquals(1, $count, 'number of consumers');
    }

    public function testImportSuccessTwo(FunctionalTester $I)
    {
        $I->amOnRoute('consumer-hub.import');
        $I->attachFile('upload', 'Consumers/Controllers/Hub/success2.csv');
        $I->click('#btn-import');

        $I->amOnRoute('consumer-hub.import');
        $I->see(Lang::choice('co.import.imported_x', 2, ['count' => 2]));

        $count = $this->user->consumers()->count();
        $I->assertEquals(2, $count, 'number of consumers');
    }

    public function testImportErrorNoUpload(FunctionalTester $I)
    {
        $I->amOnRoute('consumer-hub.import');
        $I->click('#btn-import');

        $I->amOnRoute('consumer-hub.import');
        $I->see(trans('co.import.upload_is_missing'));

        $count = $this->user->consumers()->count();
        $I->assertEquals(0, $count, 'number of consumers');
    }

    public function testBulkGroup(FunctionalTester $I)
    {
        $consumer = $this->_createConsumer($this->user);
        $consumer2 = $this->_createConsumer($this->user);
        $consumer3 = $this->_createConsumer($this->user);

        $I->amOnRoute('consumer-hub');
        $I->checkOption('#bulk-item-' . $consumer->id);
        $I->checkOption('#bulk-item-' . $consumer2->id);
        $I->selectOption('action', 'group');
        $I->click('#btn-bulk');

        $groupName = 'Group ' . time();
        $I->fillField('new_group_name', $groupName);
        $I->click('#btn-submit');

        $groups = Group::ofCurrentUser()->get();
        $I->assertEquals(1, count($groups), 'count($groups)');

        $group = $groups[0];
        $I->assertEquals($groupName, $group->name, '$group->name');

        $I->assertEquals(2, $group->consumers()->count(), '$group->consumers()->count()');

        $groupConsumerIds = $group->consumers->lists('id');
        $I->assertTrue(in_array($consumer->id, $groupConsumerIds), '$consumer->id found');
        $I->assertTrue(in_array($consumer2->id, $groupConsumerIds), '$consumer2->id found');

        // do another test to add consumers to existing group
        $I->amOnRoute('consumer-hub');
        $I->checkOption('#bulk-item-' . $consumer3->id);
        $I->selectOption('action', 'group');
        $I->click('#btn-bulk');
        $I->selectOption('group_id', $group->id);
        $I->click('#btn-submit');
        $group = Group::find($group->id);
        $I->assertEquals(3, $group->consumers()->count(), '$group->consumers()->count()');
        $groupConsumer3 = $group->consumers()->find($consumer3->id);
        $I->assertNotEmpty($groupConsumer3, '$consumer3->id found');
    }
}
