<?php namespace App\Payment;

use Redirect, Session;

class Payment
{
    /**
     * Receice an amount and redirect to payment page. We want to pass the cart
     * subtotal separately because the amount might be modified via coupon,
     * discount, etc.
     *
     * @param App\Core\Models\Cart $cart
     * @param double               $amount
     *
     * @return Redirect
     */
    public static function redirect($cart, $amount)
    {
        // Create a new transaction for this cart
        $transaction = new Models\Transaction(['amount' => $amount]);
        $transaction->cart()->associate($cart);
        $transaction->save();

        return Redirect::route('payment.index')
            ->with('transaction', $transaction);
    }

    /**
     * Get the current transaction of current request
     *
     * @return App\Payment\Models\Transaction
     */
    public static function current()
    {
        return Session::get('transaction');
    }
}
