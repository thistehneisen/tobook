<?php namespace Test\Appointment\Controllers;

use App\Appointment\Models\Booking;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\User;
use Carbon\Carbon;
use \FunctionalTester;
use Config;

/**
 * @group as
 */
class BookingCest
{
    use \Appointment\Traits\Booking;

    public function _before(FunctionalTester $I)
    {
        $I->amLoggedAs(User::find(70));
    }

    public function testIndex(FunctionalTester $I)
    {
        $I->amOnRoute('as.bookings.index');
        $I->canSeeResponseCodeIs(200);
        $I->seeNumberOfElements('.booking-row', 0);
        $I->see(trans('common.no_records'));

        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $booking = $this->_book($user, $category);
        $I->amOnRoute('as.bookings.index');
        $I->seeNumberOfElements('.booking-row', 1);

        $rowSelector = '#row-' . $booking->id;
        $I->seeElement($rowSelector);
        $I->see($booking->uuid, $rowSelector . ' > *');
        $I->see($booking->date, $rowSelector . ' > *');
        $I->see($booking->consumer->name, $rowSelector . ' > *');
        $I->see($booking->status_text, $rowSelector . ' > *');
        $I->seeElement($rowSelector . '-edit');
        $I->seeElement($rowSelector . '-delete');
    }

    public function testSearch(FunctionalTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $date = $this->_getNextDate();
        $startTime = '12:00:00';
        $booking1 = $this->_book($user, $category, $date, $startTime);
        $I->assertNotNull($booking1);
        $booking2 = $this->_book($user, $category, with(clone $date)->addDay(), $startTime);
        $I->assertNotNull($booking2);

        // search by booking 1's uuid
        $I->amOnRoute('as.bookings.index');
        $I->submitForm('#form-search', [
            'q' => $booking1->uuid,
        ]);
        $I->seeCurrentRouteIs('as.bookings.search', ['q' => $booking1->uuid]);
        $I->seeInField('q', $booking1->uuid);
        $I->seeNumberOfElements('.booking-row', 1);
        $I->seeElement('#row-' . $booking1->id);
        $I->dontSeeElement('#row-' . $booking2->id);

        // search by booking 2's date
        $I->submitForm('#form-search', [
            'q' => $booking2->date,
        ]);
        $I->seeCurrentRouteIs('as.bookings.search', ['q' => $booking2->date]);
        $I->seeInField('q', $booking2->date);
        $I->seeNumberOfElements('.booking-row', 1);
        $I->dontSeeElement('#row-' . $booking1->id);
        $I->seeElement('#row-' . $booking2->id);

        // search by start time (should return both bookings)
        $I->submitForm('#form-search', [
            'q' => $startTime,
        ]);
        $I->seeCurrentRouteIs('as.bookings.search', ['q' => $startTime]);
        $I->seeInField('q', $startTime);
        $I->seeNumberOfElements('.booking-row', 2);
        $I->seeElement('#row-' . $booking1->id);
        $I->seeElement('#row-' . $booking2->id);
    }

    public function testPagination(FunctionalTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $date = $this->_getNextDate();
        $bookings = [];
        for ($i = 0; $i < 50; $i++) {
            $bookings[$i] = $this->_book($user, $category, $date);
            $date->addDay();
        }

        $I->amOnRoute('as.bookings.index');
        $I->seeNumberOfElements('.booking-row', min(count($bookings), Config::get('view.perPage')));

        foreach ([5, 10, 20, 50] as $perPage) {
            $I->click('#per-page-' . $perPage);
            $I->seeCurrentRouteIs('as.bookings.index', ['perPage' => $perPage]);
            $I->seeNumberOfElements('.booking-row', $perPage);

            $page = 1;
            $bookingsCopied = $bookings;
            do {
                if ($page > 1) {
                    $I->amOnRoute('as.bookings.index', ['perPage' => $perPage, 'page' => $page]);
                }

                for ($i = 0; $i < $perPage; $i++) {
                    $booking = array_pop($bookingsCopied);
                    $I->seeElement('#row-' . $booking->id);
                }

                $page++;
            } while ($page <= count($bookings) / $perPage);
        }
    }

    public function testEditStatus(FunctionalTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $booking = $this->_book($user, $category);
        $statuses = Booking::getStatuses();

        foreach ($statuses as $text => $trans) {
            if ($text === 'confirmed') {
                // ignore
                continue;
            } elseif ($text === 'cancelled') {
                // ignore
                continue;
            }

            $I->amOnRoute('as.bookings.upsert', ['id' => $booking->id]);
            $I->seeInField('uuid', $booking->uuid);
            $I->seeOptionIsSelected('booking_status', $booking->status_text);

            $I->selectOption('booking_status', $trans);
            $I->click('#btn-save-booking');
            $booking = Booking::find($booking->id);

            $I->assertEquals(Booking::getStatus($text), $booking->status, 'status->' . $text);
        }
    }

    public function testEditNotes(FunctionalTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $booking = $this->_book($user, $category);

        $I->amOnRoute('as.bookings.upsert', ['id' => $booking->id]);
        $I->seeInField('uuid', $booking->uuid);
        $I->seeInField('booking_notes', $booking->notes);

        $notes = 'Notes ' . time();
        $I->fillField('booking_notes', $notes);
        $I->click('#btn-save-booking');
        $booking = Booking::find($booking->id);

        $I->assertEquals($notes, $booking->notes, 'notes');
    }

    public function testEditConsumer(FunctionalTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $booking = $this->_book($user, $category);

        $I->amOnRoute('as.bookings.upsert', ['id' => $booking->id]);
        $I->seeInField('uuid', $booking->uuid);
        $I->seeInField('first_name', $booking->consumer->first_name);
        $I->seeInField('last_name', $booking->consumer->last_name);
        $I->seeInField('email', $booking->consumer->email);
        $I->seeInField('phone', $booking->consumer->phone);
        $I->seeInField('address', $booking->consumer->address);

        $newData = [
            'first_name' => 'New First',
            'last_name' => 'Last Last',
            'email' => 'someconsumer@varaa.com',
            'phone' => '999999999',
            'address' => 'Edited Address',
        ];
        foreach ($newData as $key => $value) {
            $I->fillField($key, $value);
        }
        $I->click('#btn-save-booking');
        $booking = Booking::find($booking->id);

        foreach ($newData as $key => $value) {
            $I->assertEquals($value, $booking->consumer->$key, $key);
        }
    }

    public function testEditServiceTime(FunctionalTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $booking = $this->_book($user, $category);

        $I->amOnRoute('as.bookings.upsert', ['id' => $booking->id]);
        $I->seeInField('uuid', $booking->uuid);

        $I->seeOptionIsSelected('service_categories', $category->name);

        $service = $category->services()->first();
        $I->seeOptionIsSelected('services', $service->name);

        $serviceTime = $service->serviceTimes()->first();
        $I->seeOptionIsSelected('service_times', $service->length);
        $I->selectOption('service_times', $serviceTime->length);

        $I->sendAjaxPostRequest(route('as.bookings.service.add'), [
            'service_id' => $service->id,
            'employee_id' => $I->grabValueFrom('#employee_id'),
            'service_time' => $serviceTime->id,
            'modify_times' => $I->grabValueFrom('#modify_times'),
            'booking_date' => $I->grabValueFrom('#booking_date'),
            'start_time' => $I->grabValueFrom('#start_time'),
            'uuid' => $I->grabValueFrom('#uuid'),
            'booking_id' => $I->grabValueFrom('#booking_id'),
        ]);
        $I->click('#btn-save-booking');
        $booking = Booking::find($booking->id);

        $I->assertEquals($serviceTime->id, $booking->bookingServices()->first()->service_time_id, 'service_time_id');
    }

    public function testDelete(FunctionalTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $booking = $this->_book($user, $category);

        $booking = Booking::find($booking->id);
        $I->assertNotEmpty($booking, 'booking has been found');

        $I->amOnRoute('as.bookings.delete', ['id' => $booking->id]);
        $I->seeCurrentRouteIs('as.bookings.index');

        $booking = Booking::find($booking->id);
        $I->assertEmpty($booking, 'booking has been deleted');
    }

    public function testBulkDelete(FunctionalTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $date = $this->_getNextDate();
        $booking1 = $this->_book($user, $category, $date);
        $booking2 = $this->_book($user, $category, $date->addDay());

        $booking1 = Booking::find($booking1->id);
        $I->assertNotEmpty($booking1, 'booking 1 has been found');
        $booking2 = Booking::find($booking2->id);
        $I->assertNotEmpty($booking2, 'booking 2 has been found');

        $I->sendAjaxPostRequest(route('as.bookings.bulk'), [
            'action' => 'destroy',
            'ids' => [$booking1->id, $booking2->id]
        ]);

        $booking1 = Booking::find($booking1->id);
        $I->assertEmpty($booking1, 'booking 1 has been deleted');
        $booking2 = Booking::find($booking2->id);
        $I->assertEmpty($booking2, 'booking 2 has been deleted');
    }
}
