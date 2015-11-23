<?php namespace App\Appointment\Listeners;

use Cart;
use App\Payment\Models\Transaction;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Booking;
use App\Core\Models\CouponBooking;

class PaymentCancelledListener
{
    /**
     * When a payment was cancelled, we'll update the status of all bookings
     * to `cancelled`
     *
     * @param App\Payment\Models\Transaction $transaction
     *
     * @return void
     */
    public function handle($transaction)
    {
        $ids = [];
        $cart = $transaction->cart;
        if (!$cart) {
            // No need to live in this cruel world
            return;
        }

        // set cart status
        $cart->status = Cart::STATUS_CANCELLED;
        $cart->save();

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
                    $item->booking->status = Booking::STATUS_CANCELLED;
                    $item->booking->save();
                    
                    //Force delete a coupon booking in db
                    $couponBooking = CouponBooking::where('booking_id', '=', $item->booking->id)->first();
                    $couponBooking->forceDelete();
                }
            }
        }

        // Done
    }
}
