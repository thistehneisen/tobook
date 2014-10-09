<?php namespace App\Payment\Gateways;

abstract class Base
{
    protected $gateway;

    public function __construct()
    {
        $this->gateway = $this->getOmnipayGateway();
    }

    /**
     * Getter of $gateway
     *
     * @return Omnipay\Common\AbstractGateway
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * Create an instance of Omnipay gateway
     *
     * @return Omnipay\Common\AbstractGateway
     */
    abstract protected function getOmnipayGateway();

    /**
     * Process by sending user to gateway's website
     *
     * @return void
     */
    abstract public function process();
}
