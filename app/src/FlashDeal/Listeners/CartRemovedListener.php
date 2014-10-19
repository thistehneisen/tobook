<?php namespace App\FlashDeal\Listeners;

use App\FlashDeal\Models\FlashDealDate;
use App\Cart\CartDetail;

class CartRemovedListener
{
    /**
     * When a booking service is remove from cart, we should release that
     * booking service and booking
     *
     * @param int $id
     *
     * @return void
     */
    public function handle($id)
    {
        $cartDetail = CartDetail::find($id);
        if ($cartDetail === null) {
            // Nothing to do here
            return;
        }

        $deal = $cartDetail->model->instance;
        if ($deal instanceof FlashDealDate) {
            $deal->incrRemains($cartDetail->quantity);
        }

    }
}
