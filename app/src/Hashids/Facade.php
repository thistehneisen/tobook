<?php namespace App\Hashids;

class Facade extends \Illuminate\Support\Facades\Facade
{

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'hashids';
    }

}
