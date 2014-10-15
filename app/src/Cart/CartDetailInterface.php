<?php namespace App\Cart;

interface CartDetailInterface
{
    /**
     * Return the name of item to be displayed in cart
     *
     * @return string
     */
    public function getCartDetailName();

    /**
     * Normally this is the object itself, however, child class could customize
     * to add extra information that is useful for cart
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getCartDetailOriginal();

    /**
     * Get the quantity of this item
     *
     * @return int
     */
    public function getCartDetailQuantity();

    /**
     * Get the price of this item
     *
     * @return double
     */
    public function getCartDetailPrice();

}
