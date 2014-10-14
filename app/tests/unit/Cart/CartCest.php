<?php namespace Test\Unit\Cart;

use Codeception\Util\Debug;
use UnitTester;
use App\Cart\Cart;
use App\Core\Models\User;
use PHPUnit_Framework_Assert as Assert;

/**
 * @group cart
 */
class CartCest
{
    public function createANewCart(UnitTester $i)
    {
        $cart = Cart::make([], User::find(70));
        Assert::assertTrue($cart->isEmpty());
        Assert::assertInstanceOf('App\Cart\Cart', $cart);
    }
}
