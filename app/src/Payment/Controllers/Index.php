<?php namespace App\Payment\Controllers;

use App\Cart\Cart;
use App\Payment\Models\Transaction;
use App\Appointment\Models\BookingService;
use Event;
use Payment;
use Session;
use Input;

class Index extends Base
{
    /**
     * Show page for user to make payment
     *
     * @return View
     */
    public function index()
    {
        $transaction = Payment::current();
        return $this->render('index', [
            'transaction' => $transaction
        ]);
    }

    /**
     * User clicks on process button, prepare and redirect to paygate website
     *
     * @return Redirect
     */
    public function purchase()
    {
        return Payment::purchase();
    }

    /**
     * Receive notify request from gateway and update corresponding data
     *
     * @param string $gateway
     *
     * @return void
     */
    public function notify($gateway)
    {
        return Payment::notify($gateway);
    }

    /**
     * Display success message when a payment is completed
     *
     * @return View
     */
    public function success($cartId = null)
    {
        $cart = (!empty($cartId)) ? Cart::find($cartId) : null;

        // View to render transaction details
        $details = $this->render('details.general');
        if (Input::get('paygate') === Payment::CHECKOUT) {
            $details = $this->render('details.checkout', $this->getParamsForCheckout($cart));
        }

        return $this->render('success', [
            'cart' => $cart,
            'details' => $details,
        ]);
    }

    protected function getParamsForCheckout($cart)
    {
        $transaction = Transaction::find(Input::get('transaction_id'));
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
        return [
            'booking' => $booking,
            'bookingService' => $bookingService,
            'business' => $bookingService->user->business,
            'transaction' => $transaction,
            'consumer' => $consumer,
            'vat' => $transaction->amount * 0.24,
        ];
    }

    /**
     * When user cancelled a payment
     *
     * @param int $transactionId
     *
     * @return View
     */
    public function cancel($transactionId)
    {
        $transaction = Transaction::find($transactionId);
        if ($transaction) {
            Event::fire('payment.cancelled', $transaction);
        }

        return $this->render('cancelled');
    }
}
