<?php namespace Test\Acceptance\Appointment\Controllers;

use AcceptanceTester;
use App\Core\Models\User;
use App\Appointment\Models\ServiceCategory;
use App\Cart\CartDetail;
use Carbon\Carbon;

/**
 * @group as
 */
class FrontBookingsCest extends AbstractBooking
{
    public function testBookingTillPayment(AcceptanceTester $I)
    {
        $category = $this->categories[0];
        $date = $this->_getNextDate();
        $startAt = '12:00:00';

        $this->_feBook($I, $this->user, $category, $date, $startAt);

        $I->see($category->services()->first()->name);
        $I->click('#btn-submit');
    }

    public function testDeleteCartItem(AcceptanceTester $I)
    {
        $category = $this->categories[0];
        $date = $this->_getNextDate();
        $startAt = '12:00:00';

        $this->_feBook($I, $category, $date, $startAt);

        $I->see($category->services()->first()->name);

        $cartDetailId = $I->grabAttributeFrom('.cart-detail', 'id');
        $cartDetailId = explode('-', $cartDetailId);
        $cartDetailId = array_pop($cartDetailId);
        $I->assertGreaterThan(0, $cartDetailId, 'cart detail id');

        $cartDetail = CartDetail::find($cartDetailId);
        $I->assertNotEmpty($cartDetail, 'cart detail');

        $I->click('.js-btn-cart-remove');
        $I->wait(5);
        $cartDetail = CartDetail::find($cartDetailId);
        $I->assertEmpty($cartDetail, 'cart detail has been deleted');
    }

    protected function _feBook(AcceptanceTester $I, ServiceCategory $category, Carbon $date = null, $startAt = null)
    {
        $service = $category->services()->first();
        $employee = $this->employees[0];

        $firstName = 'First';
        $lastName = 'Last';
        $email = 'consumer' . time() . '@varaa.com';
        $phone = strval(time());

        $I->amOnPage(route('business.index', ['id' => $this->user->id], false));
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
            $I->wait(2);
        }
        $I->assertEquals($date->toDateString(), $I->grabAttributeFrom('#timetable', 'data-date'));

        $I->click('#btn-slot-' . substr(preg_replace('#[^0-9]#', '', $startAt), 0, 4));

        $I->wait(3);
        $I->click('#btn-add-cart');
        $I->wait(1);

        $I->amOnPage(route('cart.checkout', [], false));
        $I->fillField('email', $email);
        $I->fillField('#register-password', $phone);
        $I->fillField('password_confirmation', $phone);
        $I->fillField('first_name', $firstName);
        $I->fillField('last_name', $lastName);
        $I->fillField('phone', $phone);
        $I->click('#btn-register');
    }
}
