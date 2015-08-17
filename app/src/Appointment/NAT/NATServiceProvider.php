<?php namespace App\Appointment\NAT;

use Illuminate\Support\ServiceProvider;

class NATServiceProvider extends ServiceProvider
{
    /**
     * @{@inheritdoc}
     */
    public function register()
    {
        $this->app->bind('nat', function () {
            return new Service();
        });
    }
}
