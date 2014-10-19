<?php namespace App\Payment\Controllers;

use Payment;

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
    public function success()
    {
        return $this->render('success');
    }
}