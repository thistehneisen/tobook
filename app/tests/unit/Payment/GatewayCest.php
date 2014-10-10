<?php namespace Test\Unit\Payment;

use PHPUnit_Framework_Assert as Assert;
use Codeception\Util\Debug;
use App\Payment\GatewayFactory;
use UnitTester;

/**
 * @group cp
 */
class GatewayCest
{
    public function getAGateway(UnitTester $I)
    {
        $gateway = GatewayFactory::make('Skrill');
        Assert::assertInstanceOf('App\Payment\Gateways\Skrill', $gateway);
        Assert::assertInstanceOf('Omnipay\Skrill\Gateway', $gateway->getGateway());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function getANonExistingGateway(UnitTester $I)
    {
        try {
            $gateway = GatewayFactory::make('foo');
        } catch (\Exception $ex) {
            // Workaround to expect correct exception
            Assert::assertInstanceOf('InvalidArgumentException', $ex);
        }
    }
}
