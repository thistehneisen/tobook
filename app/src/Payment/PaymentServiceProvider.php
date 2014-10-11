<?php namespace App\Payment;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Include custom routes of this package
        require_once __DIR__.'/routes.php';
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Do nothing here
    }
}
