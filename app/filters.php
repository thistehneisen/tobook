<?php

/*
|--------------------------------------------------------------------------
| [Varaa] Register Form macros, View composers, and IoC containers
|--------------------------------------------------------------------------
*/
require app_path().'/macros.php';
require app_path().'/composers.php';
require app_path().'/ioc.php';

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request) {
    //
});

App::after(function ($request, $response) {
    //
});

App::missing(function ($exception) {
    return Response::view('errors.missing', array(), 404);
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {
    if (!Confide::user()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest(route('auth.login'));
        }
    }
});

Route::filter('auth-api', function () {
    Config::set('session.driver', 'array');

    /** @var \Symfony\Component\HttpFoundation\Request $request */
    $request = Auth::getRequest();
    $user = $request->getUser();
    $field = (strpos($user, '@') === false ? 'username' : 'email');

    return Auth::onceBasic($field);
});

Route::filter('auth-lc', function () {
    if (!Confide::user()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest(route('app.lc.login'));
        }
    }
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
    if (Auth::check()) {
        return Redirect::to('/');
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException();
    }
});

Route::filter('auth.admin', function () {
    if (!Entrust::hasRole('Admin') && session_get('stealthMode') === null) {
        return Redirect::route('home');
    }
});

Route::filter('premium.modules', function ($request, $response, $moduleName) {
    if (Session::get('stealthMode') === null
        && !Entrust::hasRole('Admin')
        && Confide::user()->hasModule($moduleName) === false
    ) {
        return Redirect::route('home');
    }
});

Route::filter('ajax', function () {
    if (Request::ajax() === false) {
        return Redirect::route('home');
    }
});

/*
|--------------------------------------------------------------------------
| Business filter
|--------------------------------------------------------------------------
|
| Limit access to business accounts only
|
*/
Route::filter('only.business', function () {
    $isAdmin = Entrust::hasRole('Admin') || Session::has('stealthMode');
    $user = Confide::user();
    $isBusiness = $user->is_business && $user->business->is_activated;

    if (!$isBusiness && !$isAdmin) {
        return View::make('front.message', [
            'header'  => trans('common.notice'),
            'content' => trans('common.err.not_business')
        ]);
    }
});

/*
|--------------------------------------------------------------------------
| Consumer filter
|--------------------------------------------------------------------------
|
| Limit access to consumers only
|
*/
Route::filter('only.consumer', function () {
    $isAdmin = Entrust::hasRole('Admin') || Session::has('stealthMode');
    $isConsumer = Confide::user()->hasRole(\App\Core\Models\Role::CONSUMER);
    if (!$isConsumer && !$isAdmin) {
        return Redirect::route('home');
    }
});

/*
|--------------------------------------------------------------------------
| Cart filter
|--------------------------------------------------------------------------
|
| Cart must exist before processing
|
*/
Route::filter('cart.existed', function () {
    if (Cart::current() === null) {
        return Redirect::route('home');
    }
});
