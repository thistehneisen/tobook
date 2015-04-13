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
     * @param App\Payment\Models\Transaction $transaction Current Transaction object
     * @param array                          $args        Additional params passed to individual gateway
     *
     * @return void
     */
    abstract public function purchase(\App\Payment\Models\Transaction $transaction, $args = []);

    /**
     * When a successful payment was made
     *
     * @param Omnipay\Common\Message\AbstractResponse $response
     *
     * @return void
     */
    abstract public function success($response);

    /**
     * Usually receive POST requests from paygate containing order information
     *
     * @return void
     */
    abstract public function notify();
}
