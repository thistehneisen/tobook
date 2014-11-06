<?php namespace Test\Appointment\Controllers;

use AcceptanceTester;
use Appointment\Traits\Booking;
use Appointment\Traits\Models;

/**
 * @group as
 */
class IndexCest
{
    use Models;
    use Booking;

    private $categories = [];

    public function _before()
    {
        $this->_modelsReset();
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

        $routeAsIndex = route('as.index', ['date' => $date->toDateString()], false);
        $routeLogin = route('auth.login', [], false);

        $I->amOnPage($routeAsIndex);
        $I->canSeeCurrentUrlMatches('#' . preg_quote($routeLogin, '#') . '#');
        $I->fillField('username', $this->user->email);
        $I->fillField('password', '123456');
        $I->click('#btn-login');

        // normally login will redirect to the intended url but that url doesn't have locale
        // so we will navigate ourself there
        // TODO fix the missing locale in intended redirections?
        $I->canSeeCurrentUrlMatches('#appointment\-scheduler#');
        $I->amOnPage($routeAsIndex);
        $I->wait(1);
        $I->canSeeCurrentUrlMatches('#' . preg_quote($routeAsIndex, '#') . '#');

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

        $booking = \App\Appointment\Models\Booking::where('uuid', $bookingUuid)->first();
        $I->assertNotEmpty($booking, 'booking has been created');

        $I->assertEquals($date->toDateString(), $booking->date, 'date');
        $I->assertEquals($startAt, $booking->start_at, 'start_at');

        $I->assertEquals(1, $booking->bookingServices()->count(), 'booking services');

        $bookingService = $booking->bookingServices()->first();
        $I->assertEquals($service->id, $bookingService->service_id, 'service_id');

        $consumer = $booking->consumer;
        $I->assertEquals($firstName, $consumer->first_name, 'consumer first name');
        $I->assertEquals($lastName, $consumer->last_name, 'consumer last name');
        $I->assertEquals($email, $consumer->email, 'consumer email');
        $I->assertEquals($phone, $consumer->phone, 'consumer phone');
    }
}
