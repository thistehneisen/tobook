<?php namespace App\Appointment\Listeners;

use Confide, Cart, NAT;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Booking;

class PaymentProcessListener
{
    /**
     * Update status of booking associated with this transaction
     *
     * @param App\Cart\Cart $cart
     *
     * @return void
     */
    public function handle($cart)
    {
        $ids = [];
        foreach ($cart->details as $detail) {
            if ($detail->model->instance
                instanceof \App\Appointment\Models\BookingService) {
                $ids[] = $detail->model->instance->id;
            }
        }

        // set cart status
        $cart->complete();

        if (!empty($ids)) {
            $bookingServices = BookingService::whereIn('id', $ids)
                ->with('booking')
                ->get();

            // Update related bookings
            foreach ($bookingServices as $item) {
                if ($item->booking !== null) {
                    // There is a possibility that this current user is not
                    // a consumer, who knows
                    $consumer = $cart->consumer;
                    if ($consumer !== null) {
                        // Update consumer information
                        $item->booking->consumer()->associate($consumer);
                    }

                    $item->booking->save();
                    $item->booking->saveCommission();
                }
            }
        }

        // Done
    }
}
