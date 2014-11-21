<?php namespace Test\Acceptance\Appointment\Controllers;

use AcceptanceTester;
use App\Appointment\Models\Booking;
use App\Appointment\Models\ExtraService;
use Test\Traits\Models;

/**
 * @group as
 */
class IndexCest
{
    use Models;
    use \Test\Traits\Booking;

    private $categories = [];

    public function _before()
    {
        $this->_modelsReset();
        $this->_createEmployee(2);
        $this->categories = $this->_createCategoryServiceAndExtra();
    }

    public function testBackendBooking(AcceptanceTester $I)
    {
        $category = $this->categories[0];
        $service = $category->services()->first();
        $employee = $this->employees[0];

        $date = $this->_getNextDate();
        $startAt = '12:00:00';

        $firstName = 'First';
        $lastName = 'Last';
        $email = 'consumer' . time() . '@varaa.com';
        $phone = time();

        $this->_loginAndGoToPage($I, route('as.index', ['date' => $date->toDateString()], false));
        $I->click('#btn-slot-' . $employee->id . '-' . substr(preg_replace('#[^0-9]#', '', $startAt), 0, 4));

        $I->waitForElementVisible('#select-action');
        $I->checkOption('#book');
        $I->click('#btn-continute-action');

        $I->waitForElementVisible('#booking_form');
        $I->appendField('first_name', $firstName);
        $I->appendField('last_name', $lastName);
        $I->appendField('email', $email);
        $I->appendField('phone', $phone);

        $I->click('#panel-add-service-handle');
        $I->waitForElementNotVisible('#loading');
        $I->selectOption('#service_categories', $category->name);
        $I->waitForElementNotVisible('#loading');
        $I->selectOption('#services', $service->name);
        $I->waitForElementNotVisible('#loading');
        $I->selectOption('#service_times', $service->length);
        $I->click('#btn-add-service');

        $I->waitForElementNotVisible('#loading');
        $I->see($service->name, '#added_service_name');
        $I->see($employee->name, '#added_employee_name');
        $I->see($date->toDateString(), '#added_booking_date');
        $I->see($startAt, '#added_booking_date');
        $I->see($service->price, '#added_service_price');

        $bookingUuid = $I->grabValueFrom('uuid');
        $I->click('#btn-save-booking');
        $I->wait(1);

        $booking = Booking::where('uuid', $bookingUuid)->first();
        $I->assertNotEmpty($booking, 'booking has been created');

        $I->assertEquals('backend', $booking->source, 'source');
        $I->assertEquals($date->toDateString(), $booking->date, 'date');
        $I->assertEquals($startAt, $booking->start_at, 'start_at');
        $I->assertEquals(Booking::STATUS_CONFIRM, $booking->status, 'status');
        $I->assertEquals(0, $booking->modify_times, 'modify_times');

        $I->assertEquals(1, $booking->bookingServices()->count(), 'booking services');
        $I->assertEquals(0, $booking->extraServices()->count(), 'booking extra services');

        $bookingService = $booking->bookingServices()->first();
        $I->assertEquals($service->id, $bookingService->service_id, 'service_id');

        $consumer = $booking->consumer;
        $I->assertEquals($firstName, $consumer->first_name, 'consumer first name');
        $I->assertEquals($lastName, $consumer->last_name, 'consumer last name');
        $I->assertEquals($email, $consumer->email, 'consumer email');
        $I->assertEquals($phone, $consumer->phone, 'consumer phone');
    }

    public function testEditStatus(AcceptanceTester $I)
    {
        $booking = $this->_book($this->user, $this->categories[0]);
        $I->assertNotEmpty($booking, 'booking has been created');

        $route = route('as.index', ['date' => $booking->date], false);
        $this->_loginAndGoToPage($I, $route);

        $statuses = [
            Booking::STATUS_CONFIRM,
            Booking::STATUS_PENDING,
            Booking::STATUS_ARRIVED,
            Booking::STATUS_PAID,
            Booking::STATUS_NOT_SHOW_UP,
            Booking::STATUS_CANCELLED,
        ];
        $trans = Booking::getStatuses();

        while (count($statuses) > 1) {
            $status = array_shift($statuses);
            $nextStatus = reset($statuses);

            if ($status != Booking::STATUS_CONFIRM) {
                // for confirm status, the page has already been requested
                $I->amOnPage($route);
            }

            $I->assertEquals($status, $booking->status, 'status before edit');

            $I->click('#btn-booking-' . $booking->id);
            $I->waitForElementVisible('#modify_booking_form_' . $booking->id);
            $I->seeOptionIsSelected('booking_status', $trans[$booking->getStatusText()]);

            $I->selectOption('booking_status', $trans[Booking::getStatusByValue($nextStatus)]);
            $I->click('#btn-submit-modify-form');
            $I->wait(3);

            $booking = Booking::find($booking->id);
            if ($nextStatus == Booking::STATUS_CANCELLED) {
                $I->assertEmpty($booking, 'booking has been cancelled');
            } else {
                $I->assertEquals($nextStatus, $booking->status, 'booking status has been changed');
            }
        }
    }

    public function testEditModifyTimes(AcceptanceTester $I)
    {
        $booking = $this->_book($this->user, $this->categories[0]);
        $I->assertNotEmpty($booking, 'booking has been created');

        $route = route('as.index', ['date' => $booking->date], false);
        $this->_loginAndGoToPage($I, $route);

        $I->click('#btn-booking-' . $booking->id);
        $I->waitForElementVisible('#modify_booking_form_' . $booking->id);
        $I->seeInField('modify_times', $booking->modify_time);

        $modifyTime = $booking->modify_time + 30;
        $I->fillField('modify_times', $modifyTime);
        $I->click('#btn-submit-modify-form');
        $I->wait(3);

        $booking = Booking::find($booking->id);
        $I->assertEquals($modifyTime, $booking->modify_time, 'modify_time after edit');
        $I->assertEquals($modifyTime, $booking->bookingServices()->first()->modify_time, 'service->modify_time after edit');
    }

    public function testEditExtraService(AcceptanceTester $I)
    {
        $booking = $this->_book($this->user, $this->categories[0]);
        $I->assertNotEmpty($booking, 'booking has been created');

        $service = $booking->bookingServices()->first()->service;
        $extraService = new ExtraService([
            'name' => 'Extra Service ' . time(),
            'price' => 10,
            'length' => 15,
        ]);
        $extraService->user()->associate($this->user);
        $extraService->saveOrFail();
        $service->extraServices()->attach($extraService);

        $route = route('as.index', ['date' => $booking->date], false);
        $this->_loginAndGoToPage($I, $route);

        $I->click('#btn-booking-' . $booking->id);
        $I->waitForElementVisible('#modify_booking_form_' . $booking->id);

        $I->click('//div[contains(@class, \'bootstrap-select\')]/descendant::button');
        $I->click('//ul[contains(@class, \'dropdown-menu\')]/descendant::span[text()=\'' . $extraService->name . '\']');
        $I->click('#btn-submit-modify-form');
        $I->wait(3);

        $booking = Booking::find($booking->id);
        $I->assertEquals(1, $booking->extraServices()->count(), 'extra services');
        $I->assertEquals($extraService->id, $booking->extraServices()->first()->extra_service_id, 'extra_service_id');
    }

    public function testRescheduleStartAt(AcceptanceTester $I)
    {
        $booking = $this->_book($this->user, $this->categories[0]);
        $I->assertNotEmpty($booking, 'booking has been created');

        $startAt = $booking->getStartAt()->subHour()->toTimeString();

        $route = route('as.index', ['date' => $booking->date], false);
        $this->_loginAndGoToPage($I, $route);

        $I->click('#btn-booking-' . $booking->id);
        $I->waitForElementVisible('#modify_booking_form_' . $booking->id);
        $I->click('#btn-cut-' . $booking->id);
        $I->click('#btn-cancel-' . $booking->id);
        $I->wait(1);

        $I->click('#btn-slot-' . $booking->employee->id . '-' . substr(preg_replace('#[^0-9]#', '', $startAt), 0, 4));
        $I->waitForElementVisible('#select-action');
        $I->checkOption('#paste_booking');
        $I->click('#btn-continute-action');

        $I->wait(3);

        $booking = Booking::find($booking->id);
        $I->assertEquals($startAt, $booking->start_at, 'start_at');
    }

    public function testRescheduleEmployee(AcceptanceTester $I)
    {
        $booking = $this->_book($this->user, $this->categories[0]);
        $I->assertNotEmpty($booking, 'booking has been created');

        $employee = $this->employees[1];
        $I->assertNotEquals($booking->employee_id, $employee->id, 'new employee');

        $route = route('as.index', ['date' => $booking->date], false);
        $this->_loginAndGoToPage($I, $route);

        $I->click('#btn-booking-' . $booking->id);
        $I->waitForElementVisible('#modify_booking_form_' . $booking->id);
        $I->click('#btn-cut-' . $booking->id);
        $I->click('#btn-cancel-' . $booking->id);
        $I->wait(1);

        $I->click('#btn-slot-' . $employee->id . '-' . substr(preg_replace('#[^0-9]#', '', $booking->start_at), 0, 4));
        $I->waitForElementVisible('#select-action');
        $I->checkOption('#paste_booking');
        $I->click('#btn-continute-action');

        $I->wait(3);

        $booking = Booking::find($booking->id);
        $I->assertEquals($employee->id, $booking->employee_id, 'employee_id');
    }

    public function testRescheduleDate(AcceptanceTester $I)
    {
        $date = $this->_getNextDate();
        $booking = $this->_book($this->user, $this->categories[0], $date);
        $I->assertNotEmpty($booking, 'booking has been created');

        $newDate = with(clone $date)->addDay();
        $I->assertNotEquals($date->format('Ymd'), $newDate->format('Ymd'), 'new date');

        $this->_loginAndGoToPage($I, route('as.index', ['date' => $booking->date], false));

        $I->click('#btn-booking-' . $booking->id);
        $I->waitForElementVisible('#modify_booking_form_' . $booking->id);
        $I->click('#btn-cut-' . $booking->id);
        $I->click('#btn-cancel-' . $booking->id);
        $I->wait(1);

        $I->amOnPage(route('as.index', ['date' => $newDate->toDateString()], false));
        $I->click('#btn-slot-' . $booking->employee_id . '-' . substr(preg_replace('#[^0-9]#', '', $booking->start_at), 0, 4));
        $I->waitForElementVisible('#select-action');
        $I->checkOption('#paste_booking');
        $I->click('#btn-continute-action');
        $I->wait(3);

        $booking = Booking::find($booking->id);
        $I->assertEquals($newDate->toDateString(), $booking->date, 'date');
    }

    private function _loginAndGoToPage(AcceptanceTester $I, $page)
    {
        $I->amOnPage($page);
        $I->canSeeCurrentUrlMatches('#' . preg_quote(route('auth.login', [], false), '#') . '#');
        $I->fillField('username', $this->user->email);
        $I->fillField('password', '123456');
        $I->click('#btn-login');

        // normally login will redirect to the intended url but that url doesn't have locale
        // so we will navigate ourself there
        // TODO fix the missing locale in intended redirections?
        $I->canSeeCurrentUrlMatches('#appointment\-scheduler#');
        $I->amOnPage($page);
        $I->wait(1);
        $I->canSeeCurrentUrlMatches('#' . preg_quote($page, '#') . '#');
    }
}
