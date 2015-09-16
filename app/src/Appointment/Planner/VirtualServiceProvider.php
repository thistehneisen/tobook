<?php namespace App\Appointment\Planner;

use Illuminate\Support\ServiceProvider;

class VirtualServiceProvider extends ServiceProvider
{
    /**
     * @{@inheritdoc}
     */
    public function register()
    {
        $this->app->bind('vic', function () {
            return new Virtual();
        });
    }
}
