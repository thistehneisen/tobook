<?php namespace App\Payment\Gateways;

use Omnipay\Omnipay;
use Config;

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
        $gateway->setNotifyUrl(route('payment.notify', [
            'gateway' => $gateway->getName()
        ]));

        return $gateway;
    }

    /**
     * @{@inheritdoc}
     */
    public function purchase($transaction)
    {
        return $this->gateway->purchase(func_get_args());
    }

    /**
     * @{@inheritdoc}
     */
    public function notify()
    {

    }
}
