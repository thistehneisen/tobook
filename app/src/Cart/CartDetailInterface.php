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

}
