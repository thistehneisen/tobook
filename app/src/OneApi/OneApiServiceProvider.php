<?php namespace App\OneApi;

use Illuminate\Support\ServiceProvider;

class OneApiServiceProvider extends ServiceProvider
{
    /**
     * @{@inheritdoc}
     */
    public function register()
    {
        $this->app->bind('oneapi', function($app) {
            return new OneApi();
        });
    }
}
