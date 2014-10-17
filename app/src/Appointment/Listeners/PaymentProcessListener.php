<?php namespace App\Appointment\Listeners;

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

        if (!empty($ids)) {
            $bookingServices = BookingService::whereIn('id', $ids)
                ->with('booking')
                ->get();

            // Update related bookings
            foreach ($bookingServices as $item) {
                if ($item->booking !== null) {
                    $item->booking->status = Booking::STATUS_CONFIRM;
                    $item->booking->save();
                }
            }
        }

        // Done
    }
}
