<?php namespace App\Payment\Gateways;

use Omnipay\Omnipay;
use Config, App;

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
            'details'       => [],
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
}
