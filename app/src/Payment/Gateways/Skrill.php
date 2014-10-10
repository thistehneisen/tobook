<?php namespace App\Payment\Gateways;

use Omnipay\Omnipay;
use Config, App, Validator;

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
            return App::abort(400);
        }

        // Validate logic
        if ($this->isValidRequest() === false
            || $this->isValidEmail() === false
            || $this->isValidAmount === false) {
            return App::abort(400);
        }

        // Log this request for later investigation
        // Get transaction
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
        // @todo
        return false;
    }

    /**
     * Validate payment was sent to correct email
     *
     * @return boolean
     */
    protected function isValidEmail()
    {
        return Input::get('pay_to_email') !== Config::get('services.skrill.email');
    }

    /**
     * Validate payment is a correct amount
     *
     * @return boolean
     */
    protected function isValidAmount()
    {
        return false;
    }
}
