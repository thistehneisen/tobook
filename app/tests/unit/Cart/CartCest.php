<?php namespace Test\Unit\Cart;

use Codeception\Util\Debug;
use UnitTester;
use App\Cart\Cart;
use App\Core\Models\User;
use PHPUnit_Framework_Assert as Assert;
use Mockery as m;
use Test\Unit\Cart\Stub\Item;

/**
 * @group cart
 */
class CartCest
{
    protected $user;

    public function _before()
    {
        $user = m::mock('App\Core\Models\User');
        $user->shouldReceive('find');
        // When trying to get user ID
        $user->shouldReceive('getAttribute')->with('id')->andReturn(70);
        $user->shouldReceive('save')->andReturn(true);

        $this->user = $user;
        // $this->user = User::find(70);
    }

    public function _after()
    {
        m::close();
    }

    public function createANewCart(UnitTester $i)
    {
        $cart = Cart::make([], $this->user);
        Assert::assertTrue($cart->isEmpty());
        Assert::assertInstanceOf('App\Cart\Cart', $cart);
    }

    public function addAnItemToCart(UnitTester $i)
    {
        $item = new Item;
        $item->id       = 1;
        $item->quantity = 1;
        $item->price    = 999.99;


        // Should I mock the cart?
        $cart = Cart::make([], $this->user);
        $cart->addDetail($item);

        Assert::assertFalse($cart->isEmpty());
    }
}
