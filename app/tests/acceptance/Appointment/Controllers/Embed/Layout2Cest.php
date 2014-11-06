<?php namespace Test\Appointment\Controllers\Embed;

use \AcceptanceTester;
use App\Appointment\Models\Booking;
use Appointment\Traits\Models;
use Carbon\Carbon;

/**
 * @group as
 */
class Layout2Cest
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

        $today = Carbon::today();
        $date = $this->_getNextDate();
        $startAt = '12:00:00';

        $firstName = 'First';
        $lastName = 'Last';
        $email = 'consumer' . time() . '@varaa.com';
        $phone = time();

        $I->amOnPage(route('as.embed.embed', ['hash' => $this->user->hash, 'l' => 2], false));
        $I->click('#btn-category-' . $category->id);
        $I->waitForElementVisible('#btn-service-' . $service->id);
        $I->click('#btn-service-' . $service->id);
        $I->waitForElementVisible('#btn-employee-' . $employee->id);
        $I->click('#btn-employee-' . $employee->id);

        $I->waitForElementVisible('#as-timetable');

        $layoutDate = clone $today;
        while($date->diffInDays($layoutDate) > 7) {
            $layoutDate->addWeek();
        }
        if ($layoutDate->diffInDays($today) > 0) {
            $I->click('#btn-timetable-' . $layoutDate->format('Ymd'));
            $I->wait(3);
        }

        $I->click('#btn-slot-' . $date->format('Ymd') . '-' . substr(preg_replace('#[^0-9]#', '', $startAt), 0, 4));

        $I->waitForElementVisible('#frm-customer-info');
        $I->appendField('first_name', $firstName);
        $I->appendField('last_name', $lastName);
        $I->appendField('email', $email);
        $I->appendField('phone', $phone);
        $I->click('#btn-book');

        $I->waitForElementVisible('#frm-confirm');
        $I->click('#btn-confirm');

        $I->waitForElementVisible('#as-success');

        $bookings = Booking::where('source', 'layout2')
            ->where('user_id', $this->user->id)
            ->where('employee_id', $employee->id)
            ->where('date', $date->toDateString())
            ->where('start_at', $startAt)
            ->get();
        $I->assertEquals(1, count($bookings), 'bookings');

        $booking = $bookings[0];
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
}
