<?php namespace Test\Functional\Payment;

use PHPUnit_Framework_Assert as Assert;
use Codeception\Util\Debug;
use App\Core\Models\Cart;
use FunctionalTester;
use Payment, Session;

/**
 * @group cp
 */
class PaymentCest extends \Test\Functional\Base
{
    protected $amount = 999;
    protected $userId = 70;

    public function redirectToPaymentPage(FunctionalTester $I)
    {
        $cart = Cart::make([], $this->userId);
        $result = Payment::redirect($cart, $this->amount);

        Assert::assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
        Assert::assertTrue(Session::has('transaction'));

        $transaction = Payment::current();
        Assert::assertEquals($transaction->amount, $this->amount);
        Assert::assertEquals($transaction->cart->user->id, $this->userId);
    }
}
