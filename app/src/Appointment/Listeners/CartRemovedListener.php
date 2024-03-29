<?php namespace App\Appointment\Listeners;

use App\Appointment\Models\BookingService;
use App\Appointment\Models\Booking;
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
        if ($cartDetail === null || $cartDetail->model === null) {
            // Nothing to do here
            return;
        }

        $bookingService = $cartDetail->model->instance;
        if ($bookingService instanceof BookingService) {
            // Remove booking first
            if ($bookingService->booking !== null) {
                $bookingService->booking->delete();
            }

            $bookingService->delete();
        }

    }
}
