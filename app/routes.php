<?php

Route::pattern('id', '[0-9]+');

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

Route::group(['before' => 'auth'], function() {
    Route::resource('consumers', 'App\LoyaltyCard\Controllers\Consumer');
});

Route::group(['prefix' => 'intro'], function () {
    Route::get('website-list', [
        'as' => 'intro-website-list',
        'uses' => 'App\Controllers\Home@websiteList',
    ]);

    Route::get('loyalty', [
        'as' => 'intro-loyalty',
        'uses' => 'App\Controllers\Home@loyalty',
    ]);

    Route::get('timeslot', [
        'as'    => 'intro-timeslot',
        'uses'  => 'App\Controllers\Home@timeslot',
    ]);

    Route::get('customer-registration', [
        'as' => 'intro-customer-registration',
        'uses' => 'App\Controllers\Home@marketingTools',
    ]);

    Route::get('cashier', [
        'as' => 'intro-cashier',
        'uses' => 'App\Controllers\Home@cashier',
    ]);

    Route::get('marketing-tools', [
        'as' => 'intro-marketing-tools',
        'uses' => 'App\Controllers\Home@marketingTools',
    ]);
});

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
    ], function () {

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

        Route::get('appointment-scheduler', [
            'as' => 'appointment.index',
            'uses' => 'App\Controllers\Services@appointment'
        ]);

        Route::get('loyalty-program', [
            'as' => 'loyalty.index',
            'uses' => 'App\Controllers\Services@loyalty'
        ]);

        Route::get('marketing-tool', [
            'as' => 'marketing.index',
            'uses' => 'App\Controllers\Services@marketing'
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
    'before' => ['auth', 'auth.admin']
], function () {

    Route::get('/', [
        'as' => 'admin.index',
        'uses' => 'App\Controllers\Admin\Dashboard@index'
    ]);

    Route::get('settings', [
        'as' => 'admin.settings.index',
        'uses' => 'App\Controllers\Admin\Settings@index'
    ]);

    Route::post('settings', [
        'as' => 'admin.settings.index',
        'uses' => 'App\Controllers\Admin\Settings@doUpdate'
    ]);

    // User model uses a different way to save data
    Route::post('users/{id}', [
        'uses' => 'App\Controllers\Admin\Users@doEdit'
    ]);

    Route::get('users/login/{id}', [
        'as' => 'admin.users.login',
        'uses' => 'App\Controllers\Admin\Users@stealSession'
    ]);

    // CRUD actions
    Route::get('{model}', [
        'as' => 'admin.crud.index',
        'uses' => 'App\Controllers\Admin\Crud@index'
    ]);

    Route::get('{model}/search', [
        'as' => 'admin.crud.search',
        'uses' => 'App\Controllers\Admin\Crud@search'
    ]);

    Route::get('{model}/{id}', [
        'as' => 'admin.crud.edit',
        'uses' => 'App\Controllers\Admin\Crud@edit'
    ]);

    Route::post('{model}/{id}', [
        'uses' => 'App\Controllers\Admin\Crud@doEdit'
    ]);

    Route::get('{model}/delete/{id}', [
        'as'   => 'admin.crud.delete',
        'uses' => 'App\Controllers\Admin\Crud@delete'
    ]);

});
