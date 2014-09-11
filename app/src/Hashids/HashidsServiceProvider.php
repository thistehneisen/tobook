<?php namespace App\Hashids;

use Illuminate\Support\ServiceProvider;

class HashidsServiceProvider extends ServiceProvider
{
    /**
     * @{@inheritdoc}
     */
    public function register()
    {
        $this->app->bind('hashids', function ($app) {
            return new Hashids (
                $app['config']->get('app.key'),
                $app['config']->get('varaa.hashid.length'),
                $app['config']->get('varaa.hashid.alphabet')
            );
        });
    }
}
