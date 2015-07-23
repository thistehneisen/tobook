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

App::error(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $exception, $code) {
    Log::warning('Wrong HTTP method accessing', [
        'url' => URL::current(),
        'method' => Request::method(),
    ]);

    return Redirect::to('/');
});

App::error(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception, $code) {
    Log::warning($exception->getMessage(), ['url' => URL::current()]);

    return Response::view('errors.missing', [], 404);
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
require app_path().'/filters.php';

/*
|--------------------------------------------------------------------------
| Localize existing URLs
|--------------------------------------------------------------------------
| We want to maintain the URL in the format of
| varaa.com/<locale>/<param1>/<param2>... and set system's locale based
| on the request URI
|
*/
App::before(function ($request) {
    // Get default locale of this instance, and fallback to `en`
    $language = Config::get('app.locale', 'en');

    // Get current request URI
    $uri = $request->server->get('REQUEST_URI');

    $segments = explode('/', $uri);
    if (isset($segments[1])) {
        // Assume that all locale segments have length of 2 characters
        // If the 2nd segment of this URI is not an locale value
        if (strlen($segments[1]) > 2) {
            // Attempt to attach the default locale to this URL
            array_splice($segments, 1, 0, [$language]);
        }
    }

    // Remove the locale part
    $newUri = substr(implode('/', $segments), 3);
    // Set new URI, so that the request dispatcher knows which routes to match
    // and invoke. Internally, L4 will see the request as normal without locale
    // prefix
    $request->server->set('REQUEST_URI', $newUri);

    // Now get locale from URI
    if (!empty($segments[1])) {
        $routeLanguage = $segments[1];
        if (in_array($routeLanguage, Config::get('varaa.supported_languages'))) {
            $language = $routeLanguage;
        }
    }

    // Set system locale
    Config::set('app.locale', $language);
    App::setLocale($language);

    // Push to native session for other modules to use
    @session_start();
    $_SESSION['varaa_locale'] = $language;
});

/*
|--------------------------------------------------------------------------
| Require The Event Handlers
|--------------------------------------------------------------------------
| Quick way to attach event handlers
| @todo: Refactor modules to service providers and attach in there.
|
*/
require app_path().'/events.php';

// use varaa-legacy auth driver for old password support
Auth::extend('varaa-legacy', function ($app) {
    $model = $app['config']['auth.model'];

    return new App\Core\UserProvider($app['hash'], $model);
});
