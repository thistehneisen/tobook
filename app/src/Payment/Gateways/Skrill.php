<?php namespace App\Payment\Gateways;

use Omnipay\Omnipay;
use Config;
use App;
use Validator;
use Input;
use Log;
use Event;
use App\Payment\Models\Transaction;

class Skrill extends AbstractGateway
{
    /**
     * Predefined status constants
     * @see Skrill Payment Gateway Integration Guide (v6.8)
     */
    const STATUS_FAILED    = -2;
    const STATUS_CANCELLED = -1;
    const STATUS_PENDING   = 0;
    const STATUS_PROCESSED = 2;


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
            'returnUrl'     => route('payment.success', ['id' => $transaction->cart->id]),
            'cancelUrl'     => route('payment.cancel', ['id' => $transaction->id]),
            // TODO: Populate with cart details
            'details'       => ['foo' => 'bar'],
        ];

        // Extract consumer information from the cart and send along to Skrill
        // server, so that our beloved consumer doesn't have to type it again
        if ($transaction->cart && ($consumer = $transaction->cart->consumer)) {
            $options['customerEmail']      = $consumer->email;
            $options['customerFirstName']  = $consumer->first_name;
            $options['customerLastName']   = $consumer->last_name;
            $options['customerAddress1']   = $consumer->address;
            $options['customerPhone']      = $consumer->phone;
            $options['customerCity']       = $consumer->city;
            $options['customerPostalCode'] = $consumer->postcode;
        }

        return $this->gateway->purchase($options)->send();
    }

    /**
     * @{@inheritdoc}
     */
    public function success($response)
    {
        // Wont do anything because there's no post-back data from Skrill
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
            'transaction_id' => 'required',
            'mb_amount'      => 'required',
            'mb_currency'    => 'required',
            'status'         => 'required',
            'md5sig'         => 'required',
        ]);

        if ($v->fails()) {
            Log::warning('Abort due to missing required data',
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
            || !$this->isProcessed()
            || !$this->isValidAmount($transaction)) {
            return App::abort(400);
        }

        // Update relevant data
        $transaction->status = Transaction::STATUS_SUCCESS;
        // Not sure if this can be used as ref
        $transaction->reference = Input::get('md5sig');
        $transaction->paygate = 'Skrill';
        $transaction->save();

        // Fire success event, so that related data know how to update
        // themselves
        Event::fire('payment.success', [$transaction]);

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
            Log::warning('Abort due to malformed MD5 signature',
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
            Log::warning('Abort due to invalid recipient email',
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
            Log::warning('Abort due to invalid amount',
                ['context' => 'Skrill post-back']);
        }

        return $result;
    }

    /**
     * Validate if payment has been processed
     *
     * @return boolean
     */
    protected function isProcessed()
    {
        $result = (int) Input::get('status') === static::STATUS_PROCESSED;
        if ($result === false) {
            Log::warning('Abort because payment was not processed',
                ['context' => 'Skrill post-back']);
        }

        return $result;
    }
}
