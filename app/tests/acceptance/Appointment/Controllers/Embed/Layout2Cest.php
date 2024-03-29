<?php namespace Test\Acceptance\Appointment\Controllers\Embed;

use \AcceptanceTester;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Cart\Cart;
use App\Core\Models\User;
use Carbon\Carbon;
use Test\Acceptance\Appointment\Controllers\AbstractBooking;

/**
 * @group as
 */
class Layout2Cest extends AbstractBooking
{

    public function testSuccess(AcceptanceTester $I)
    {
        $category = $this->categories[0];
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
        while ($date->diffInDays($layoutDate) > 7) {
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

        $cart = Cart::where('user_id', $this->user->id)->first();
        $I->assertNotEmpty($cart, 'cart');
        $I->assertEquals(Cart::STATUS_COMPLETED, $cart->status, '$cart->status');
    }

    public function testAbandoned(AcceptanceTester $I)
    {
        $category = $this->categories[0];
        $service = $category->services()->first();
        $employee = $this->employees[0];

        $today = Carbon::today();
        $date = $this->_getNextDate();
        $startAt = '12:00:00';

        $I->amOnPage(route('as.embed.embed', ['hash' => $this->user->hash, 'l' => 2], false));
        $I->click('#btn-category-' . $category->id);
        $I->waitForElementVisible('#btn-service-' . $service->id);
        $I->click('#btn-service-' . $service->id);
        $I->waitForElementVisible('#btn-employee-' . $employee->id);
        $I->click('#btn-employee-' . $employee->id);

        $I->waitForElementVisible('#as-timetable');

        $layoutDate = clone $today;
        while ($date->diffInDays($layoutDate) > 7) {
            $layoutDate->addWeek();
        }
        if ($layoutDate->diffInDays($today) > 0) {
            $I->click('#btn-timetable-' . $layoutDate->format('Ymd'));
            $I->wait(3);
        }

        $I->click('#btn-slot-' . $date->format('Ymd') . '-' . substr(preg_replace('#[^0-9]#', '', $startAt), 0, 4));
        $I->waitForElementVisible('#frm-customer-info');

        $bookingServices = BookingService::where('user_id', $this->user->id)
            ->where('employee_id', $employee->id)
            ->where('date', $date->toDateString())
            ->where('start_at', $startAt)
            ->get();
        $I->assertEquals(1, count($bookingServices), 'booking services');

        $bookingService = $bookingServices[0];
        $I->assertEquals($service->id, $bookingService->service_id, 'service_id');
        $I->assertEquals(0, $bookingService->consumer_id, 'consumer_id');

        $cutoff = Carbon::today()->addDay();
        Cart::scheduledUnlock($cutoff);

        $bookingService = BookingService::find($bookingService->id);
        $I->assertEmpty($bookingService, 'booking service has been deleted');
    }

    public function testResourceBooking(AcceptanceTester $I)
    {
        $this->user = User::find(70);
        $this->initData(false, true);//init resources
        $this->initCustomTime();
        $I->amLoggedAs($this->user);

        $category = $this->category;
        $service = $category->services()->first();
        $employee = $service->employees()->first();

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
        while ($date->diffInDays($layoutDate) > 7) {
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

        $cart = Cart::where('user_id', $this->user->id)->first();
        $I->assertNotEmpty($cart, 'cart');
        $I->assertEquals(Cart::STATUS_COMPLETED, $cart->status, '$cart->status');
    }

    public function testBookingWithRooms(AcceptanceTester $I)
    {
        $user = User::find(70);
        $this->initData(false, false, true);//init resources
        $this->initCustomTime();

        $category = $this->category;
        $employee  = Employee::find(63);
        $employee2 = Employee::find(64);
        $employee3 = Employee::find(65);
        $service   = $this->service;

        $I->amLoggedAs($user);
        $date = $this->getDate();
        $startTime = '09:00';
        $booking1 = $this->makeBooking($date, $startTime, $service, $employee);
        $booking2 = $this->makeBooking($date, $startTime, $service, $employee2);
        $I->assertEquals($date->toDateString(), $booking1->date);
        $I->assertEquals($date->toDateString(), $booking2->date);

        $I->assertEquals($service->rooms()->count(), 2);

        $I->amOnPage(route('as.embed.embed', ['hash' => $this->user->hash, 'l' => 2], false));
        $I->click('#btn-category-' . $category->id);
        $I->waitForElementVisible('#btn-service-' . $service->id);
        $I->click('#btn-service-' . $service->id);
        $I->waitForElementVisible('#btn-employee-' . $employee3->id);
        $I->click('#btn-employee-' . $employee3->id);

        $I->waitForElementVisible('#as-timetable');

        $I->dontSeeElement('a', ['id'=> 'btn-slot-' . $date->format('Ymd') .'-0900']);

        //Add more room and the time will be available
        $this->addMoreRoom($user, $service);

        $I->assertEquals($service->rooms()->count(), 3);

        $availableRoom = Booking::getAvailableRoom(64, $service, null, $date->toDateString(), $date->copy()->hour(12)->minute(0), $date->copy()->hour(13)->minute(0));
        $I->assertNotEmpty($availableRoom);
        $I->amOnPage(route('as.embed.embed', ['hash' => $this->user->hash, 'l' => 2, 'x'=> 2], false));
        $I->click('#btn-category-' . $category->id);
        $I->waitForElementVisible('#btn-service-' . $service->id);
        $I->click('#btn-service-' . $service->id);
        $I->waitForElementVisible('#btn-employee-' . $employee3->id);
        $I->click('#btn-employee-' . $employee3->id);

        $I->waitForElementVisible('#as-timetable');

        $I->seeElement('a', ['id'=> 'btn-slot-' . $date->format('Ymd') .'-0900']);
    }
}
