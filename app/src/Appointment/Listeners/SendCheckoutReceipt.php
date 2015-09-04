<?php namespace App\Appointment\Listeners;

use App\Appointment\Models\BookingService;
use Mail;
use Payment;
use Log;

class SendCheckoutReceipt
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
        Log::debug('Prepare to send receipt');
        // No need to send receipts to paygates other than Checkout
        if ($transaction->paygate !== Payment::CHECKOUT) {
            Log::debug('This is not a Checkout transaction. Abort.');
            return;
        }

        $cart = $transaction->cart;
        if ($cart === null) {
            Log::debug('Cannot complete AS booking because the cart is empty', ['transaction' => $transaction]);
            // Nothing to do with this
            return;
        }

        // Complete the cart
        $cart->complete();

        $bookingServiceIds = $cart->details->lists('model_id');
        $bookingService = BookingService::whereIn('id', $bookingServiceIds)
            ->with('booking')
            ->get()
            ->filter(function ($bookingService) {
                return $bookingService->booking !== null;
            })
            ->first();

        if ($bookingService === null) {
            // Don't know what to do next
            return;
        }

        $booking = $bookingService->booking;
        $consumer = $booking->consumer;
        $params = [
            'booking' => $booking,
            'bookingService' => $bookingService,
            'business' => $bookingService->user->business,
            'transaction' => $transaction,
            'consumer' => $consumer,
            'vat' => $transaction->amount * 0.24,
        ];


        return Mail::send('emails.receipt', $params, function ($msg) use ($booking, $consumer) {
            $msg->to($consumer->email)
                ->subject('Receipt for your purchase #'.$booking->uuid);
        });
    }
}
