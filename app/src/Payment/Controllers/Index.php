<?php namespace App\Payment\Controllers;

use App\Cart\Cart;
use App\Payment\Models\Transaction;
use App\Appointment\Models\BookingService;
use App\Core\Models\BusinessCommission;
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

        $data = $this->getParamsForCheckout($cart);

        if (Input::get('paygate') === Payment::CHECKOUT) {
            $details = $this->render('details.checkout', $data);
        }

        $this->updateCommission($cart, $data);

        return $this->render('success', [
            'cart' => $cart,
            'details' => $details,
        ]);
    }

    /**
     * Update commission data after payement is successful
     *
     * @param Cart $cart
     * @param array $data
     *
     * @return void
     */
    protected function updateCommission($cart, $data)
    {
        $transaction = $data['transaction'];
        $booking = $data['booking'];

        $paymentType = (!empty($transaction->paygate))
            ? BusinessCommission::PAYMENT_FULL
            : BusinessCommission::PAYMENT_VENUE;

        BusinessCommission::updateCommission($booking, $paymentType);
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
            'vat' => (!empty($transaction)) ? $transaction->amount * 0.24 : 0,
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
