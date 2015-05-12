<?php namespace App\Appointment\Listeners;

use Cart;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Booking;

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
        if ($cart === null) {
            Log::debug('Cannot complete AS booking because the cart is empty', ['transaction' => $transaction]);
            // Nothing to do with this
            return;
        }

        // Complete the cart
        $cart->complete();

        // Find all booking service IDs
        $bookingServiceIds = $cart->details->lists('model_id');
        $bookingServices = BookingService::whereIn('id', $bookingServiceIds)
            ->with('booking')
            ->get();

        // Update related bookings
        foreach ($bookingServices as $item) {
            if ($item->booking !== null) {

                if(!$cart->isDepositPayment) {
                    $item->booking->status = Booking::STATUS_PAID;
                } else {
                    $item->booking->status = Booking::STATUS_CONFIRM;
                    $item->booking->deposit = $cart->depositTotal;
                }

                $item->booking->save();
            }
        }

        // Done
    }

}
