<?php namespace App\Olut;

use Illuminate\Support\ServiceProvider;

class OlutServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->package('olut', 'olut', __DIR__);
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Do nothing here
    }
}
