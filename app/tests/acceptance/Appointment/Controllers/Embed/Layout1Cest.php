<?php namespace Test\Acceptance\Appointment\Controllers\Embed;

use \AcceptanceTester;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Core\Models\User;
use App\Cart\Cart;
use Carbon\Carbon;
use Test\Acceptance\Appointment\Controllers\AbstractBooking;
use Config;

/**
 * @group as
 */
class Layout1Cest extends AbstractBooking
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

        $I->amOnPage(route('as.embed.embed', ['hash' => $this->user->hash], false));
        if ($today->month == $date->month) {
            $I->click(sprintf('//td[normalize-space()=\'%s\']', $date->day));
        } else {
            $I->click('//th[contains(@class, \'next\')]');
            $I->wait(1);
            $I->click(sprintf('//td[normalize-space()=\'%s\']', $date->day));
        }

        $I->wait(1);
        $I->seeCurrentUrlMatches('#date=' . $date->toDateString() . '#');
        $I->click('#btn-category-' . $category->id);

        $I->waitForElementVisible('#category-services-' . $category->id);
        $I->click('#btn-service-' . $service->id);

        $I->waitForElementVisible('#service-' . $category->id . '-' . $service->id);
        $I->click('#btn-service-' . $service->id . '-time-default');

        $I->click('#btn-add-service-' . $service->id);

        $I->wait(1);
        $I->seeCurrentUrlMatches('#service_id=\d+?&service_time=default&date=' . $date->toDateString() . '#');
        $I->click('#btn-slot-' . $employee->id . '-' . substr(preg_replace('#[^0-9]#', '', $startAt), 0, 4));
        $I->click(trans('as.embed.make_appointment'));

        $I->wait(1);
        $I->seeCurrentUrlMatches('#action=checkout#');
        $I->appendField('first_name', $firstName);
        $I->appendField('last_name', $lastName);
        $I->appendField('email', $email);
        $I->appendField('phone', $phone);
        $I->click('#btn-checkout-submit');

        $I->wait(1);
        $I->seeCurrentUrlMatches('#action=confirm#');
        $I->click('#btn-confirm-booking');

        $I->wait(3);

        $bookings = Booking::where('source', 'layout1')
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

        $I->amOnPage(route('as.embed.embed', ['hash' => $this->user->hash], false));
        if ($today->month == $date->month) {
            $I->click(sprintf('//td[normalize-space()=\'%s\']', $date->day));
        } else {
            $I->click('//th[contains(@class, \'next\')]');
            $I->wait(1);
            $I->click(sprintf('//td[normalize-space()=\'%s\']', $date->day));
        }

        $I->wait(1);
        $I->seeCurrentUrlMatches('#date=' . $date->toDateString() . '#');
        $I->click('#btn-category-' . $category->id);

        $I->waitForElementVisible('#category-services-' . $category->id);
        $I->click('#btn-service-' . $service->id);

        $I->waitForElementVisible('#service-' . $category->id . '-' . $service->id);
        $I->click('#btn-service-' . $service->id . '-time-default');

        $I->click('#btn-add-service-' . $service->id);

        $I->wait(1);
        $I->seeCurrentUrlMatches('#service_id=\d+?&service_time=default&date=' . $date->toDateString() . '#');
        $I->click('#btn-slot-' . $employee->id . '-' . substr(preg_replace('#[^0-9]#', '', $startAt), 0, 4));
        $I->click(trans('as.embed.make_appointment'));

        $I->wait(1);
        $I->seeCurrentUrlMatches('#action=checkout#');

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

    /**
     * Using selenium as a service
     * http://pietervogelaar.nl/ubuntu-14-04-install-selenium-as-service-headless
     */
    public function testEndTime(AcceptanceTester $I)
    {
        $this->category = null;
        $this->service = null;

        $this->initData(false);
        $this->initCustomTime();

        $user = User::find(70);
        $category = $this->category;
        $service = $category->services()->first();
        $employee = $this->employee;


        $I->assertEquals($service->length, 60);
        $I->assertEquals($service->before, 0);
        //This service has 15 mins after the actual service length
        $I->assertEquals($service->after, 15);

        $date = Carbon::today();
        if($date->dayOfWeek == Carbon::SUNDAY) {
            $date->addDays(1);
        } else if($date->dayOfWeek == Carbon::SATURDAY) {
            $date->addDays(2);
        }
        $startAt = '1600';

        $firstName = 'First';
        $lastName = 'Last';
        $email = 'consumer' . time() . '@varaa.com';
        $phone = time();
        $I->assertEquals($category->user->id, $user->id);
        $I->amOnPage(route('as.embed.embed', ['hash' => $user->hash, 'date'=> $date->toDateString()], false));
        $I->click('#btn-category-' . $category->id);
        $I->wait(1);
        $I->see("Hiusjuuritutkimus");
        $I->click('#btn-service-' . $service->id);
        $I->click(sprintf('//a[@data-service-id=%s][@data-service-time="default"]', $service->id));
        $I->click(sprintf('#btn-add-service-%s', $service->id));
        $I->wait(1);
        $I->seeInCurrentUrl(sprintf('service_id=%d&service_time=default&date=%s', $service->id, $date->toDateString()));
        $I->click(sprintf('//*[@id="btn-slot-%s-%s"]', $employee->id, $startAt));
        $I->see("16:00", sprintf('//span[@class="start-time-%s"]', $employee->id));
        $I->see("16:45", sprintf('//span[@class="end-time-%s"]', $employee->id));
    }
}
