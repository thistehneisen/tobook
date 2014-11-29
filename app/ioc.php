<?php
// The default search provider is ElasticSearch
// In case there's change, just swap the new class name
// @see http://laravel.com/docs/4.2/ioc#automatic-resolution
App::bind('App\Search\ProviderInterface', 'App\Search\Providers\ElasticSearch');
