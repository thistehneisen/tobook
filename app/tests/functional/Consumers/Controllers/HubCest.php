<?php namespace Test\Consumers\Controllers;

use App\Appointment\Models\AsConsumer;
use App\Consumers\Models\Consumer;
use App\Core\Models\Role;
use Appointment\Traits\Booking;
use Appointment\Traits\Models;
use FunctionalTester;
use Lang;

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

        $consumer = new Consumer([
            'first_name' => 'First ' . time(),
            'last_name' => 'Last',
            'email' => 'consumer_' . time() . '@varaa.com',
            'phone' => time(),
            'hash' => '',
        ]);
        $consumer->saveOrFail();

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
}
