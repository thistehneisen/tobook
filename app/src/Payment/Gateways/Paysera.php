<?php namespace App\Payment\Gateways;

use App;
use App\Payment\Models\Transaction;
use Config;
use Exception;
use WebToPay;

class Paysera extends Base
{
    /**
     * @{@inheritdoc}
     */
    protected function getOmnipayGateway()
    {
        // We don't have the Omnipay plugin for Paysera yet
        return null;
    }

    /**
     * @{@inheritdoc}
     */
    public function purchase(\App\Payment\Models\Transaction $transaction, $args = [])
    {
        $request = WebToPay::redirectToPayment([
            'projectid'     => Config::get('services.paysera.id'),
            'sign_password' => Config::get('services.paysera.password'),
            'orderid'       => $transaction->id,
            'amount'        => $transaction->amount * 100, // The amount must be in cents
            'currency'      => $transaction->currency,
            'accepturl'     => route('payment.success'),
            'cancelurl'     => route('payment.cancel', ['id' => $transaction->id]),
            'callbackurl'   => route('payment.notify', ['gateway' => 'paysera']),
            'test'          => (int) Config::get('app.debug'),
        ], true);
    }

    /**
     * @{@inheritdoc}
     */
    public function success($response)
    {
        // Do nothing
    }

    /**
     * Receive POST request from Paysera and process order
     *
     * @see https://developers.paysera.com/en/payments/current#integration-via-library
     *
     * @return void
     */
    public function notify()
    {
        try {
            $response = WebToPay::checkResponse(Input::all(), [
                'projectid'     => Config::get('services.paysera.id'),
                'sign_password' => Config::get('services.paysera.password'),
            ]);

            if ($response['type'] !== 'macro') {
                Log::error('Receive Paysera transaction different from `macro`', $response);
                exit;
            }

            $orderId  = $response['orderid'];
            $amount   = $response['amount'];
            $currency = $response['currency'];
            $status   = $response['status'];

            // Get transaction
            $transaction = Transaction::findOrFail($orderId);
            if (!$this->isProcessed($status)
                || !$this->isValidAmount($amount, $currency, $transaction)) {
                return App::abort(400);
            }

            // Update relevant data
            $transaction->status = Transaction::STATUS_SUCCESS;
            // Not sure if this can be used as ref
            $transaction->reference = $response['requestid'];
            $transaction->paygate = 'Paysera';
            $transaction->save();

            // Fire success event, so that related data know how to update
            // themselves
            Event::fire('payment.success', [$transaction]);

            echo 'OK';
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['context' => 'Paysera post-back']);
        }
        exit;
    }

    /**
     * Validate if the transaction has been processed successfully
     *
     * @param int $status
     *
     * @return boolean
     */
    protected function isProcessed($status)
    {
        // Quoted from Paysera's documentation
        // https://developers.paysera.com/en/payments/current#integration-via-specification
        //
        // 0 - payment has no been executed
        // 1 - payment successful
        // 2 - payment order accepted, but not yet executed
        // 3 - additional payment information

        $result = (int) $status === 1;

        if (!$result) {
            Log::warning('Abort because the transaction has not been processed',
                ['context' => 'Paysera post-back']);
        }

        return $result;
    }

    /**
     * Validate if we receive the correct amount
     *
     * @param dobule                         $amount
     * @param string                         $currency
     * @param App\Payment\Models\Transaction $transaction
     *
     * @return boolean
     */
    protected function isValidAmount($amount, $currency, $transaction)
    {
        $result = $currency === $transaction->currency
            && (double) $amount === $transaction->amount;

        if (!$result) {
            Log::warning('Abort due to invalid amount',
                ['context' => 'Paysera post-back']);
        }

        return $result;
    }
}
