<?php namespace App\Payment;

class GatewayFactory
{
    public static function make($name)
    {
        $className = 'App\Payment\Gateways\\'.ucfirst($name);
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Unsupported gateway '.$name);
        }

        return new $className();
    }
}
