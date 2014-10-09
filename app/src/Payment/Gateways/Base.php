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
     * @param App\Paygate\Models\Transaction $transaction
     *
     * @return void
     */
    abstract public function purchase($transaction);

    /**
     * Receive notify request from paygate and update corresponding data
     *
     * @return void
     */
    abstract public function notify();
}
