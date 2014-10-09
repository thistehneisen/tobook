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
        $gateway->setNotifyUrl('');
        $gateway->setTestMode(Config::get('app.debug'));

        return $gateway;
    }

    /**
     * @{@inheritdoc}
     */
    public function process()
    {
        return $this->gateway->purchase(func_get_args());
    }
}
