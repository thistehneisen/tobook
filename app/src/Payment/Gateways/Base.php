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
     * Purchase the provided transaction
     *
     * @param array $args Arguments as required by Omnipay
     *
     * @return void
     */
    abstract public function purchase($args);

    /**
     * When a successful payment was made
     *
     * @param Omnipay\Common\Message\AbstractResponse $response
     *
     * @return void
     */
    abstract public function success($response);
}
