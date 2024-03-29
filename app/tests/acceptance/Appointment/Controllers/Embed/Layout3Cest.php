<?php namespace Test\Acceptance\Appointment\Controllers\Embed;

use \AcceptanceTester;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;
use App\Appointment\Models\BookingService;
use App\Cart\Cart;
use App\Core\Models\User;
use Carbon\Carbon;
use Validator;
use Test\Acceptance\Appointment\Controllers\AbstractBooking;

/**
 * @group as
 */
class Layout3Cest extends AbstractBooking
{
    public function testSuccess(AcceptanceTester $I)
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

        $I->amOnPage(route('as.embed.embed', ['hash' => $this->user->hash, 'l' => 3], false));
        $I->checkOption('input[name=category_id]', strval($category->id));
        $I->waitForElementVisible('#as-category-' . $category->id . '-services');
        $I->click('#btn-service-' . $service->id);
        $I->waitForElementVisible('#service-times-' . strval($service->id));
        $I->checkOption('input[name=service_id]', strval($service->id));

        $I->waitForElementVisible('#as-step-2 .as-employee');
        $I->checkOption('input[name=employee_id]', strval($employee->id));

        $I->waitForElementVisible('#timetable');
        while ($I->grabAttributeFrom('#timetable', 'data-date') !== $date->toDateString()) {
            $I->click('#btn-date-next');
            $I->wait(2);
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
        $I->waitForElementVisible('.text-success');
        $I->see(trans('as.embed.success'));

        $bookings = Booking::where('source', 'layout3')
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

        $date = $this->_getNextDate();
        $startAt = '12:00:00';

        // $I->amOnPage(route('as.embed.embed', ['hash' => $this->user->hash, 'l' => 3], false));
        $I->amOnPage(route('business.index', ['id' => $this->user->id, 'slug' => 'asdasds'], false));
        $I->checkOption('input[name=category_id]', strval($category->id));
        $I->waitForElementVisible('#as-category-' . $category->id . '-services');
        $I->click('#btn-service-' . $service->id);
        $I->waitForElementVisible('#service-times-' . strval($service->id));
        $I->checkOption('input[name=service_id]', strval($service->id));

        $I->waitForElementVisible('#as-step-2 .as-employee');
        $I->checkOption('input[name=employee_id]', strval($employee->id));

        $I->waitForElementVisible('#timetable');
        while ($I->grabAttributeFrom('#timetable', 'data-date') !== $date->toDateString()) {
            $I->click('#btn-date-next');
            $I->wait(3);
        }
        $I->assertEquals($date->toDateString(), $I->grabAttributeFrom('#timetable', 'data-date'));

        $I->click('#btn-slot-' . substr(preg_replace('#[^0-9]#', '', $startAt), 0, 4));

        // $I->waitForElementVisible('#as-form-checkout');
        $I->waitForElementVisible('#btn-add-cart');
        $I->click('#btn-add-cart');

        $bookingServices = BookingService::where('user_id', $this->user->id)
            ->where('employee_id', $employee->id)
            ->where('date', $date->toDateString())
            ->where('start_at', $startAt)
            ->get();

        //$I->assertEquals(1, $bookingServices->count(), 'booking services');

        $bookingService = $bookingServices[0];
        $I->assertEquals($service->id, $bookingService->service_id, 'service_id');
        $I->assertEquals(null, $bookingService->consumer_id, 'consumer_id');

        $cutoff = \Carbon\Carbon::today()->addDay();
        // \Cart::scheduledUnlock($cutoff);

        // $bookingService = BookingService::find($bookingService->id);
        // $I->assertEmpty($bookingService, 'booking service has been deleted');
    }

    public function testBookingResource(AcceptanceTester $I)
    {
        // $this->initData(false, true);//init resource with service
        // $this->initCustomTime();
        // $date = $this->_getNextDate();
        // $category = $this->category;
        // $service = $category->services()->first();

        // $employee = Employee::find(64);
        // $this->_book($this->user, $this->category, $date, '9:00', $employee);

        // $startAt = '09:00';

        // $I->amOnPage(route('as.embed.embed', ['hash' => $this->user->hash, 'l' => 3], false));

        // $I->selectOption('input[name=category_id]', strval($category->id));
        // $I->waitForElementVisible('#btn-service-'. $service->id);
        // $I->click('#btn-service-' . $service->id);
        // $I->waitForElementVisible('#service-times-' . strval($service->id));
        // $I->checkOption('input[name=service_id]', strval($service->id));

        // $I->waitForElementVisible('#as-step-2 .as-employee');
        // $I->checkOption('input[name=employee_id]', strval($employee->id));

        // $I->waitForElementVisible('#timetable');
        // while ($I->grabAttributeFrom('#timetable', 'data-date') !== $date->toDateString()) {
        //     $I->click('#btn-date-next');
        //     $I->wait(2);
        // }
        // $I->assertEquals($date->toDateString(), $I->grabAttributeFrom('#timetable', 'data-date'));

        // $I->dontSeeElement('#btn-slot-'.$startAt);
        //$I->click('#btn-slot-' . substr(preg_replace('#[^0-9]#', '', $startAt), 0, 4));
    }

    public function testEnterNothingInStep4(AcceptanceTester $I)
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

        $I->amOnPage(route('as.embed.embed', ['hash' => $this->user->hash, 'l' => 3], false));
        $I->checkOption('input[name=category_id]', strval($category->id));
        $I->waitForElementVisible('#as-category-' . $category->id . '-services');
        $I->click('#btn-service-' . $service->id);
        $I->waitForElementVisible('#service-times-' . strval($service->id));
        $I->checkOption('input[name=service_id]', strval($service->id));

        $I->waitForElementVisible('#as-step-2 .as-employee');
        $I->checkOption('input[name=employee_id]', strval($employee->id));

        $I->waitForElementVisible('#timetable');
        while ($I->grabAttributeFrom('#timetable', 'data-date') !== $date->toDateString()) {
            $I->click('#btn-date-next');
            $I->wait(2);
        }
        $I->assertEquals($date->toDateString(), $I->grabAttributeFrom('#timetable', 'data-date'));

        $I->click('#btn-slot-' . substr(preg_replace('#[^0-9]#', '', $startAt), 0, 4));

        $I->waitForElementVisible('#as-form-checkout');
        $I->appendField('first_name', '');
        $I->appendField('last_name', '');
        $I->appendField('email', '');
        $I->appendField('phone', '');
        $I->click('#btn-checkout-submit');

        $fields = [
            'first_name' => '',
            'last_name'  => '',
            'phone'      => '',
            'email'      => '',
        ];

        $validators = [
            'first_name' => ['required'],
            'last_name'  => ['required'],
            'phone'      => ['required', 'numeric'],
            'email'      => ['required', 'email'],
        ];

        $validator = Validator::make($fields, $validators);
        $v = $validator->errors();
        // $I->see('Yhteystietosi');
        // $I->assertEquals('first name kenttä on pakollinen.', $v->get('first_name')[0]);
        $I->see($v->get('first_name')[0]);
        $I->see($v->get('last_name')[0]);
        $I->see($v->get('phone')[0]);
        $I->see($v->get('email')[0]);
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


        $I->amOnPage(route('as.embed.embed', ['hash' => $user->hash, 'l' => 3], false));
        $I->checkOption(sprintf('//*[@id="as-category-%s"]/label/input', $category->id));
        $I->waitForElementVisible('#as-category-' . $category->id . '-services');
        $I->click('#btn-service-' . $service->id);
        $I->waitForElementVisible('#service-times-' . strval($service->id));
        $I->checkOption(sprintf('//*[@id="service-times-%s"]/p/label/input', $service->id));

        $I->waitForElementVisible('#as-step-2 .as-employee');
        $I->checkOption('//*[@name="employee_id"]', strval($employee->id));

        $I->waitForElementVisible('#timetable');
        while ($I->grabAttributeFrom('#timetable', 'data-date') !== $date->toDateString()) {
            $I->click('#btn-date-next');
            $I->wait(2);
        }
        $I->assertEquals($date->toDateString(), $I->grabAttributeFrom('#timetable', 'data-date'));

        $I->dontSeeElement('button', ['id'=> 'btn-slot-0900']);


        //Add more room and the time will be available
        $this->addMoreRoom($user, $service);
        $I->assertEquals($service->rooms()->count(), 3);
        $availableRoom = Booking::getAvailableRoom(64, $service, null, $date->toDateString(), $date->copy()->hour(12)->minute(0), $date->copy()->hour(13)->minute(0));
        $I->assertNotEmpty($availableRoom);

        $I->amOnPage(route('as.embed.embed', ['hash' => $user->hash, 'l' => 3], false));
        $I->checkOption(sprintf('//*[@id="as-category-%s"]/label/input', $category->id));
        $I->waitForElementVisible('#as-category-' . $category->id . '-services');
        $I->click('#btn-service-' . $service->id);
        $I->waitForElementVisible('#service-times-' . strval($service->id));
        $I->checkOption(sprintf('//*[@id="service-times-%s"]/p/label/input', $service->id));

        $I->waitForElementVisible('#as-step-2 .as-employee');
        $I->checkOption('//*[@name="employee_id"]', strval($employee3->id));

        $I->waitForElementVisible('#timetable');
        while ($I->grabAttributeFrom('#timetable', 'data-date') !== $date->toDateString()) {
            $I->click('#btn-date-next');
            $I->wait(2);
        }
        $I->assertEquals($date->toDateString(), $I->grabAttributeFrom('#timetable', 'data-date'));

        $I->seeElement('button', ['id'=> 'btn-slot-0900']);
    }
}
