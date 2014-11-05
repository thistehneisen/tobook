<?php namespace Test\Appointment\Controllers\Embed;

use \AcceptanceTester;
use App\Appointment\Models\Booking;
use Appointment\Traits\Models;
use Carbon\Carbon;

/**
 * @group as
 */
class Layout3Cest
{
    use Models;
    use \Appointment\Traits\Booking;

    public function _before()
    {
        $this->_modelsReset();
    }

    public function testSuccess(AcceptanceTester $I)
    {
        $categories = $this->_createCategoryServiceAndExtra();
        $category = $categories[0];
        $service = $category->services()->first();
        $employee = $this->employees[0];

        $date = $this->_getNextDate();
        $startAt = '12:00:00';

        $firstName = 'First';
        $lastName = 'Last';
        $email = 'consumer' . time() . '@varaa.com';
        $phone = time();

        $I->amOnPage(route('as.embed.embed', ['hash' => $this->user->hash, 'l' => 3], false));
        $I->selectOption('input[name=category_id]', $category->id);
        $I->waitForElementVisible('#as-category-' . $category->id . '-services');
        $I->click('#btn-service-' . $service->id);
        $I->waitForElementVisible('#service-times-' . $service->id);
        $I->selectOption('input[name=service_id]', $service->id);

        $I->waitForElementVisible('#as-step-2 .as-employee');
        $I->selectOption('input[name=employee_id]', $employee->id);

        $I->waitForElementVisible('#timetable');
        while ($I->grabAttributeFrom('#timetable', 'data-date') !== $date->toDateString()) {
            $I->click('#btn-date-next');
            $I->wait(1);
        }
        $I->assertEquals($date->toDateString(), $I->grabAttributeFrom('#timetable', 'data-date'));

        $I->click('#btn-slot-' . substr(preg_replace('#[^0-9]#', '', $startAt), 0, 4));

        $I->waitForElementVisible('#as-form-checkout');
        $I->appendField('first_name', $firstName);
        $I->appendField('last_name', $lastName);
        $I->appendField('email', $email);
        $I->appendField('phone', $phone);
        $I->click('#btn-checkout-submit');

        $I->waitForElementVisible('#as-form-confirm');
        $I->click('#btn-confirm-submit');

        $I->see(trans('as.embed.success'));

        $bookings = Booking::where('source', 'layout3')
            ->where('user_id', $this->user->id)
            ->where('employee_id', $employee->id)
            ->where('date', $date->toDateString())
            ->where('start_at', $startAt)
            ->get();
        $I->assertEquals(1, count($bookings), 'bookings');

        $booking = $bookings[0];
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
