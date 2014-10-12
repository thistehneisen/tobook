<?php namespace App\Appointment\Listeners;

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
            // Nothing to do with this
            return;
        }

        // Find all booking service IDs
        $bookingServiceIds = $cart->details->lists('item');
        $bookingServices = BookingService::whereIn('id', $bookingServiceIds)
            ->with('booking')
            ->get();

        // Update related bookings
        foreach ($bookingServices as $item) {
            if ($item->booking !== null) {
                $item->booking->status = Booking::STATUS_PAID;
                $item->booking->save();
            }
        }

        // Done
    }

}
