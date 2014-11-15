<?php namespace App\Appointment\NAT;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * @{@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'nat';
    }
}
