<?php namespace App\FlashDeal\Listeners;

use Cart;
use App\Payment\Models\Transaction;

class PaymentSuccessListener
{
    /**
     * Update status of booking associated with this transaction
     *
     * @param App\Payment\Models\Transaction $transaction
     *
     * @return void
     */
    public function handle($transaction)
    {
        $cart = $transaction->cart;
        if (!$cart) {
            // Nothing to do with this
            return;
        }

        // set cart status
        $cart->status = Cart::STATUS_COMPLETED;
        $cart->save();
    }

}
