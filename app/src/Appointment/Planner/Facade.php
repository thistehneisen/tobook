<?php namespace App\Appointment\Planner;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * @{@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'vic';
    }
}
