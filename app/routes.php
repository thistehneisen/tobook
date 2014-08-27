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
    'uses'  => 'App\Core\Controllers\Home@index'
]);

Route::group(['prefix' => 'intro'], function () {
    Route::get('website-list', [
        'as' => 'intro-website-list',
        'uses' => 'App\Core\Controllers\Home@websiteList',
    ]);

    Route::get('loyalty', [
        'as' => 'intro-loyalty',
        'uses' => 'App\Core\Controllers\Home@loyalty',
    ]);

    Route::get('timeslot', [
        'as'    => 'intro-timeslot',
        'uses'  => 'App\Core\Controllers\Home@timeslot',
    ]);

    Route::get('customer-registration', [
        'as' => 'intro-customer-registration',
        'uses' => 'App\Core\Controllers\Home@marketingTools',
    ]);

    Route::get('cashier', [
        'as' => 'intro-cashier',
        'uses' => 'App\Core\Controllers\Home@cashier',
    ]);

    Route::get('marketing-tools', [
        'as' => 'intro-marketing-tools',
        'uses' => 'App\Core\Controllers\Home@marketingTools',
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
        'uses' => 'App\Core\Controllers\Auth@login'
    ]);

    Route::post('login', [
        'uses' => 'App\Core\Controllers\Auth@doLogin'
    ]);

    Route::get('register', [
        'as' => 'auth.register',
        'uses' => 'App\Core\Controllers\Auth@register'
    ]);

    Route::post('register', [
        'uses' => 'App\Core\Controllers\Auth@doRegister'
    ]);

    Route::get('thank-you', [
        'as' => 'auth.register.done',
        'uses' => 'App\Core\Controllers\Auth@showThankYou'
    ]);

    Route::get('confirm/{code}', [
        'as' => 'auth.confirm',
        'uses' => 'App\Core\Controllers\Auth@confirm'
    ]);

    Route::get('logout', [
        'as' => 'auth.logout',
        'uses' => 'App\Core\Controllers\Auth@logout'
    ]);

    Route::get('forgot-password', [
        'as' => 'auth.forgot',
        'uses' => 'App\Core\Controllers\Auth@forgot'
    ]);

    Route::post('forgot-password', [
        'uses' => 'App\Core\Controllers\Auth@doForgot'
    ]);

    Route::get('reset-password/{token}', [
        'as' => 'auth.reset',
        'uses' => 'App\Core\Controllers\Auth@reset'
    ]);

    Route::post('reset-password/{token}', [
        'uses' => 'App\Core\Controllers\Auth@doReset'
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
        'uses' => 'App\Core\Controllers\Dashboard@index'
    ]);

    Route::get('profile', [
        'as' => 'user.profile',
        'uses' => 'App\Core\Controllers\User@profile'
    ]);

    Route::post('profile', [
        'uses' => 'App\Core\Controllers\User@changeProfile'
    ]);

    Route::group([
        'before' => [''], // Attach a filter to check payment
        'prefix' => 'services'
    ], function () {

        Route::get('cashier', [
            'as' => 'cashier.index',
            'uses' => 'App\Core\Controllers\Services@cashier'
        ]);

        Route::get('restaurant-booking', [
            'as' => 'restaurant.index',
            'uses' => 'App\Core\Controllers\Services@restaurant'
        ]);

        Route::get('timeslot', [
            'as' => 'timeslot.index',
            'uses' => 'App\Core\Controllers\Services@timeslot'
        ]);

        Route::get('appointment-scheduler', [
            'as'     => 'appointment.index',
            'before' => 'premium.modules:appointment',
            'uses'   => 'App\Core\Controllers\Services@appointment'
        ]);

        Route::get('loyalty-program', [
            'as' => 'loyalty.index',
            'uses' => 'App\Core\Controllers\Services@loyalty'
        ]);

        Route::get('marketing-tool', [
            'as' => 'marketing.index',
            'uses' => 'App\Core\Controllers\Services@marketing'
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| Loyalty Card routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'loyalty-card',
    'before' => ['auth']
], function () {

    Route::resource('consumers', 'App\LoyaltyCard\Controllers\Consumer', [
        'names' => [
            'index' => 'lc.consumers.index',
            'create' => 'lc.consumers.create',
            'edit' => 'lc.consumers.edit',
            'store' => 'lc.consumers.store',
            'update' => 'lc.consumers.update',
        ]
    ]);

    Route::resource('offers', 'App\LoyaltyCard\Controllers\Offer', [
        'names' => [
            'index' => 'lc.offers.index',
            'create' => 'lc.offers.create',
            'edit' => 'lc.offers.edit',
            'store' => 'lc.offers.store',
            'update' => 'lc.offers.update',
        ]
    ]);

    Route::resource('vouchers', 'App\LoyaltyCard\Controllers\Voucher', [
        'names' => [
            'index' => 'lc.vouchers.index',
            'create' => 'lc.vouchers.create',
            'edit' => 'lc.vouchers.edit',
            'store' => 'lc.vouchers.store',
            'update' => 'lc.vouchers.update',
        ]
    ]);
});

/*
|--------------------------------------------------------------------------
| Module Consumers routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'consumers',
    'before' => ['auth']
], function() {

    Route::get('/', [
        'as' => 'co.index',
        'uses' => 'App\Consumers\Controllers\Index@index'
    ]);

    Route::get('edit/{id}', [
        'as' => 'co.edit',
        'uses' => 'App\Consumers\Controllers\Index@edit'
    ]);

    Route::post('edit/{id}', [
        'uses' => 'App\Consumers\Controllers\Index@doEdit'
    ]);

    Route::post('bulk', [
        'as'   => 'co.bulk',
        'uses' => 'App\Consumers\Controllers\Index@bulk'
    ]);

});

/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'api',
    'before' => 'auth.basic',
], function() {
    Route::group([
        'prefix' => 'lc',
    ], function() {
        Route::resource('offers', 'App\API\LoyaltyCard\Controllers\Offer');
        Route::resource('vouchers', 'App\API\LoyaltyCard\Controllers\Voucher');
        Route::resource('consumers', 'App\API\LoyaltyCard\Controllers\Consumer');

        Route::group([
            'prefix' => 'use',
        ], function () {
            Route::post('consumers/{id}', [
                'uses' => 'App\API\LoyaltyCard\Controllers\Consumer@update'
            ]);

            Route::post('consumers/{id}', [
                'uses' => 'App\API\LoyaltyCard\Controllers\Consumer@update'
            ]);
        });
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
        'uses' => 'App\Core\Controllers\Admin\Dashboard@index'
    ]);

    Route::get('settings', [
        'as' => 'admin.settings.index',
        'uses' => 'App\Core\Controllers\Admin\Settings@index'
    ]);

    Route::post('settings', [
        'as' => 'admin.settings.index',
        'uses' => 'App\Core\Controllers\Admin\Settings@doUpdate'
    ]);

    // User model uses a different way to save data
    Route::post('users/{id}', [
        'uses' => 'App\Core\Controllers\Admin\Users@doEdit'
    ]);

    Route::get('users/login/{id}', [
        'as' => 'admin.users.login',
        'uses' => 'App\Core\Controllers\Admin\Users@stealSession'
    ]);

    // Premium modules
    Route::get('users/modules/{id}', [
        'as' => 'admin.users.modules',
        'uses' => 'App\Core\Controllers\Admin\Users@modules'
    ]);

    Route::post('users/modules/{id}', [
        'uses' => 'App\Core\Controllers\Admin\Users@enableModule'
    ]);

    Route::get('users/modules/activation/{userId}/{id}', [
        'as'   => 'admin.users.modules.activation',
        'uses' => 'App\Core\Controllers\Admin\Users@toggleActivation'
    ]);

    // CRUD actions
    Route::get('{model}', [
        'as' => 'admin.crud.index',
        'uses' => 'App\Core\Controllers\Admin\Crud@index'
    ]);

    Route::get('{model}/create', [
        'as' => 'admin.crud.create',
        'uses' => 'App\Core\Controllers\Admin\Crud@create'
    ]);

    Route::post('{model}/create', [
        'uses' => 'App\Core\Controllers\Admin\Crud@doCreate'
    ]);

    Route::get('{model}/search', [
        'as' => 'admin.crud.search',
        'uses' => 'App\Core\Controllers\Admin\Crud@search'
    ]);

    Route::get('{model}/{id}', [
        'as' => 'admin.crud.edit',
        'uses' => 'App\Core\Controllers\Admin\Crud@edit'
    ]);

    Route::post('{model}/{id}', [
        'uses' => 'App\Core\Controllers\Admin\Crud@doEdit'
    ]);

    Route::get('{model}/delete/{id}', [
        'as'   => 'admin.crud.delete',
        'uses' => 'App\Core\Controllers\Admin\Crud@delete'
    ]);

});
