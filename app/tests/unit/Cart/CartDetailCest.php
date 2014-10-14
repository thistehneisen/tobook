<?php namespace Test\Unit\Cart;

use UnitTester;
use App\Cart\CartDetail;
use App\Core\Models\User;
use PHPUnit_Framework_Assert as Assert;
use Test\Unit\Cart\Stub\Item;

/**
 * @group cart
 */
class CartDetailCest
{
    public function createANewCartDetail(UnitTester $i)
    {
        $item = new Item;
        $item->quantity = 1;
        $item->price    = 999.99;

        $detail = CartDetail::make($item);
        Assert::assertInstanceOf('App\Cart\CartDetail', $detail);
        Assert::assertEquals($detail->quantity, $item->quantity);
        Assert::assertEquals($detail->price, $item->price);
        Assert::assertEquals($detail->model_id, null);
        Assert::assertEquals($detail->model_type, 'Test\Unit\Cart\Stub\Item');
        Assert::assertEquals($detail->name, 'Foo');
    }
}
