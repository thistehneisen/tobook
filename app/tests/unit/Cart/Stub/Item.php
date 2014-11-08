<?php namespace Test\Unit\Cart\Stub;

use AppModel;
use App\Cart\CartDetailInterface;
use App\Cart\CartDetail;

class Item extends AppModel implements CartDetailInterface
{
    public function getCartDetailName()
    {
        return 'Foo';
    }

    public function getCartDetailOriginal()
    {
        return $this;
    }

    public function getCartDetailQuantity()
    {
        return $this->quantity;
    }

    public function getCartDetailPrice()
    {
        return $this->price;
    }

    public function unlockCartDetail(CartDetail $cartDetail)
    {
    }
}
