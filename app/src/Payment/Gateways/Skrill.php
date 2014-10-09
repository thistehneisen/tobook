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
    public function purchase($args)
    {
        $args['language'] = Config::get('app.locale');
        $args['details']  = ['foo' => 'bar'];
        return $this->gateway->purchase($args)->send();
    }

    /**
     * @{@inheritdoc}
     */
    public function success($response)
    {
        dd($response);
    }
}
