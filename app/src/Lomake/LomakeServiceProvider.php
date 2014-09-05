<?php namespace App\Lomake;

use Illuminate\Support\ServiceProvider;

class LomakeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->package('varaa/lomake', 'varaa-lomake', __DIR__);
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->bind('lomake', function ($app) {
            return new Lomake();
        });
    }
}
