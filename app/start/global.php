<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

    app_path().'/commands',
    app_path().'/controllers',
    app_path().'/models',
    app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function (Exception $exception, $code) {
    Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function () {
    return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

App::before(function($request)
{
    // Purpose:
    // We want to maintain the URL in the format of
    // varaa.com/<locale>/<param1>/<param2>... and set system's locale based
    // on the request URI


    // Get current request URI
    $uri = $request->server->get('REQUEST_URI');
    // Remove the locale part
    $newUri = substr($uri, 3);
    // Set new URI, so that the request dispatcher knows which routes to match
    // and invoke. Internally, L4 will see the request as normal without locale
    // prefix
    $request->server->set('REQUEST_URI', $newUri);

    // Ge default browser language
    $language = substr($request->server->get('HTTP_ACCEPT_LANGUAGE'), 0, 2);

    // Now get locale from URI
    $segments = explode('/', $uri);
    if (!empty($segments[1])) {
        $routeLanguage = $segments[1];
        if (in_array($routeLanguage, Config::get('varaa.languages'))) {
            $language = $routeLanguage;
        }
    }

    // Set system locale
    Config::set('app.locale', $language);
    App::setLocale($language);
});

require app_path().'/filters.php';
