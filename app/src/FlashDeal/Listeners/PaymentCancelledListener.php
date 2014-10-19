<?php namespace App\FlashDeal\Listeners;

use App\Payment\Models\Transaction;
use App\FlashDeal\Models\FlashDealDate;

class PaymentCancelledListener
{
    /**
     * When a payment was cancelled, we'll release those flash deals
     *
     * @param App\Payment\Models\Transaction $transaction
     *
     * @return void
     */
    public function handle($transaction)
    {
        $cart = $transaction->cart;
        if (!$cart) {
            // No need to live in this cruel world
            return;
        }

        $quantity = [];
        foreach ($cart->details as $detail) {
            if ($detail->model->instance instanceof FlashDealDate) {
                if (!isset($quantity[$detail->model_id])) {
                    $quantity[$detail->model_id] = 0;
                }

                $quantity[$detail->model_id] += $detail->quantity;
            }
        }

        // Reincrease
        foreach ($quantity as $model_id => $value) {
            FlashDealDate::where('id', $model_id)->increment('remains', $value);
        }

        // Done
    }
}
