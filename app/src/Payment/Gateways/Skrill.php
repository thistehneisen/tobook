<?php namespace App\Payment\Gateways;

use Omnipay\Omnipay;
use Config, App, Validator, Input, Log;
use App\Payment\Models\Transaction;

class Skrill extends Base
{
    /**
     * @{@inheritdoc}
     */
    public function getOmnipayGateway()
    {
        $gateway = Omnipay::create('Skrill');
        $gateway->setEmail(Config::get('services.skrill.email'));
        $gateway->setPassword(Config::get('services.skrill.password'));
        $gateway->setTestMode(Config::get('app.debug'));

        return $gateway;
    }

    /**
     * @{@inheritdoc}
     */
    public function purchase(\App\Payment\Models\Transaction $transaction, $args = [])
    {
        $options = [
            'language'      => strtoupper(App::getLocale()),
            'amount'        => $transaction->amount,
            'currency'      => $transaction->currency,
            'transactionId' => $transaction->id,
            'notifyUrl'     => route('payment.notify', ['gateway' => 'skrill']),
            // TODO: Populate with cart details
            'details'       => ['foo' => 'bar'],
        ];

        return $this->gateway->purchase($options)->send();
    }

    /**
     * @{@inheritdoc}
     */
    public function success($response)
    {
        dd($response);
    }

    /**
     * Handle notify request from Skrill server
     *
     * @see Skrill Payment Gateway Integration Guide (v6.8)
     * @return string
     */
    public function notify()
    {
        // Validate data first
        $v = Validator::make(Input::all(), [
            'pay_to_email'   => 'required',
            'merchant_id'    => 'required',
            'transaction_id' => 'required',
            'mb_amount'      => 'required',
            'mb_currency'    => 'required',
            'status'         => 'required',
            'md5sig'         => 'required',
        ]);

        if ($v->fails()) {
            Log::info('Abort due to missing required data',
                ['context' => 'Skrill post-back']);
            return App::abort(400);
        }

        // Log this request for later investigation
        Log::info('Received post-back request from Skrill', Input::all());

        // Get transaction
        $transaction = Transaction::findOrFail(Input::get('transaction_id'));

        // Validate logic
        if (!$this->isValidRequest()
            || !$this->isValidEmail()
            || !$this->isValidAmount($transaction)) {
            return App::abort(400);
        }

        // Update relevant data

        // Complete, exit to prevent any additional output
        exit;
    }

    /**
     * Validate this request really comes from Skrill server
     *
     * @return boolean
     */
    protected function isValidRequest()
    {
        $hash = strtoupper(md5(
            Input::get('merchant_id').
            Input::get('transaction_id').
            strtoupper(md5(Config::get('services.skrill.secret'))).
            Input::get('mb_amount').
            Input::get('mb_currency').
            Input::get('status')
        ));

        $result = Input::get('md5sig') === $hash;

        if ($result === false) {
            Log::info('Abort due to malformed MD5 signature',
                ['context' => 'Skrill post-back']);
        }
        return $result;
    }

    /**
     * Validate payment was sent to correct email
     *
     * @return boolean
     */
    protected function isValidEmail()
    {
        $result = Input::get('pay_to_email')
            === Config::get('services.skrill.email');

        if ($result === false) {
            Log::info('Abort due to invalid recipient email',
                ['context' => 'Skrill post-back']);
        }

        return $result;
    }

    /**
     * Validate payment is a correct amount
     *
     * @param App\Payment\Models\Transaction $transaction
     *
     * @return boolean
     */
    protected function isValidAmount($transaction)
    {
        $result = $transaction->amount === (double) Input::get('mb_amount')
            && $transaction->currency === Input::get('mb_currency');

        if ($result === false) {
            Log::info('Abort due to invalid amount',
                ['context' => 'Skrill post-back']);
        }

        return $result;
    }
}
