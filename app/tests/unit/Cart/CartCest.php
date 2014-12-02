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
        $user->shouldReceive('toArray');
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
        Assert::assertEquals($cart->total, 0.0);
        Assert::assertEquals($cart->total_items, 0);
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
        Assert::assertEquals($cart->total, 999.99);
        Assert::assertEquals($cart->total_items, 1);
    }

    public function addMultipleItemsToCart(UnitTester $i)
    {
        $foo = new Item;
        $foo->id = 1;
        $foo->quantity = 2;
        $foo->price = 10;

        $bar = new Item;
        $bar->id = 3;
        $bar->quantity = 4;
        $bar->price = 10.955;

        $cart = Cart::make([], $this->user);
        $cart->addDetails([$foo, $bar]);

        Assert::assertEquals($cart->details->count(), 2, 'Total items of the cart');
        Assert::assertEquals($cart->total, 63.82, 'Total amount of money');
        Assert::assertEquals($cart->total_items, 6, 'Total amount of money');

        // Try to get back data from database
        $dbCart = Cart::find($cart->id);
        $f = $dbCart->details->first();
        Assert::assertEquals($f->model_id, $foo->id);
        Assert::assertEquals($f->quantity, $foo->quantity);
        Assert::assertEquals($f->price, $foo->price);
    }

    public function completeACart(UnitTester $i)
    {
        $cart = Cart::make([], $this->user);
        $cart->complete();

        Assert::assertEquals($cart->status, Cart::STATUS_COMPLETED);
    }
}
