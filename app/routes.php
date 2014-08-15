<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', [
    'as'    => 'home',
    'uses'  => 'App\Controllers\Home@index'
]);

Route::get('/loyality_guide', [
    'as'    => 'loyalty_guide',
    'uses'  => 'App\Controllers\Home@loyalty',
]);

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'auth'], function () {

    Route::get('login', [
        'as' => 'auth.login',
        'uses' => 'App\Controllers\Auth@login'
    ]);

    Route::post('login', [
        'uses' => 'App\Controllers\Auth@doLogin'
    ]);

    Route::get('register', [
        'as' => 'auth.register',
        'uses' => 'App\Controllers\Auth@register'
    ]);

    Route::post('register', [
        'uses' => 'App\Controllers\Auth@doRegister'
    ]);

    Route::get('thank-you', [
        'as' => 'auth.register.done',
        'uses' => 'App\Controllers\Auth@showThankYou'
    ]);

    Route::get('confirm/{code}', [
        'as' => 'auth.confirm',
        'uses' => 'App\Controllers\Auth@confirm'
    ]);

    Route::get('logout', [
        'as' => 'auth.logout',
        'uses' => 'App\Controllers\Auth@logout'
    ]);

    Route::get('forgot-password', [
        'as' => 'auth.forgot',
        'uses' => 'App\Controllers\Auth@forgot'
    ]);

    Route::post('forgot-password', [
        'uses' => 'App\Controllers\Auth@doForgot'
    ]);

    Route::get('reset-password/{token}', [
        'as' => 'auth.reset',
        'uses' => 'App\Controllers\Auth@reset'
    ]);

    Route::post('reset-password/{token}', [
        'uses' => 'App\Controllers\Auth@doReset'
    ]);

});

/*
|--------------------------------------------------------------------------
| User routes
|--------------------------------------------------------------------------
*/
Route::group([
    'before' => ['auth']
], function () {

    Route::get('dashboard', [
        'as' => 'dashboard.index',
        'uses' => 'App\Controllers\Dashboard@index'
    ]);

    Route::get('profile', [
        'as' => 'user.profile',
        'uses' => 'App\Controllers\User@profile'
    ]);

    Route::post('profile', [
        'uses' => 'App\Controllers\User@changeProfile'
    ]);

    Route::group([
        'before' => [''], // Attach a filter to check payment
        'prefix' => 'services'
    ], function() {

        Route::get('cashier', [
            'as' => 'cashier.index',
            'uses' => 'App\Controllers\Services@cashier'
        ]);

        Route::get('restaurant-booking', [
            'as' => 'restaurant.index',
            'uses' => 'App\Controllers\Services@restaurant'
        ]);

        Route::get('timeslot', [
            'as' => 'timeslot.index',
            'uses' => 'App\Controllers\Services@timeslot'
        ]);


    });


});
/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => Config::get('admin.prefix'),
    'before' => ['auth', 'super.user']
], function() {

    Route::get('/', [
        'as' => 'admin.index',
        'uses' => 'App\Controllers\Admin\Dashboard@index'
    ]);

    Route::get('{model}', [
        'as' => 'admin.crud.index',
        'uses' => 'App\Controllers\Admin\Crud@index'
    ]);

});
