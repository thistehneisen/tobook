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

    Route::get('online-booking', [
        'as'    => 'intro-online-booking',
        'uses'  => 'App\Core\Controllers\Home@onlineBooking',
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
            'as'     => 'cashier.index',
            'before' => ['premium.modules:cashier'],
            'uses'   => 'App\Core\Controllers\Services@cashier'
        ]);

        Route::get('restaurant-booking', [
            'as'     => 'restaurant.index',
            'before' => ['premium.modules:restaurant'],
            'uses'   => 'App\Core\Controllers\Services@restaurant'
        ]);

        Route::get('timeslot', [
            'as'     => 'timeslot.index',
            'before' => ['premium.modules:timeslot'],
            'uses'   => 'App\Core\Controllers\Services@timeslot'
        ]);

        Route::get('appointment-scheduler', [
            'as'     => 'appointment.index',
            'before' => ['premium.modules:appointment'],
            'uses'   => 'App\Core\Controllers\Services@appointment'
        ]);

        Route::get('loyalty-program', [
            'as' => 'loyalty.index',
            'before' => ['premium.modules:loyalty'],
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
            'destroy' => 'lc.consumers.delete',
        ]
    ]);

    Route::resource('offers', 'App\LoyaltyCard\Controllers\Offer', [
        'names' => [
            'index' => 'lc.offers.index',
            'create' => 'lc.offers.create',
            'edit' => 'lc.offers.edit',
            'store' => 'lc.offers.store',
            'update' => 'lc.offers.update',
            'destroy' => 'lc.offers.delete',
        ]
    ]);

    Route::resource('vouchers', 'App\LoyaltyCard\Controllers\Voucher', [
        'names' => [
            'index' => 'lc.vouchers.index',
            'create' => 'lc.vouchers.create',
            'edit' => 'lc.vouchers.edit',
            'store' => 'lc.vouchers.store',
            'update' => 'lc.vouchers.update',
            'destroy' => 'lc.vouchers.delete',
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
        'prefix' => 'v1.0',
    ], function () {
        Route::group([
            'prefix' => 'lc',
        ], function() {
            Route::resource('offers', 'App\API\v1_0\LoyaltyCard\Controllers\Offer');
            Route::resource('vouchers', 'App\API\v1_0\LoyaltyCard\Controllers\Voucher');
            Route::resource('consumers', 'App\API\v1_0\LoyaltyCard\Controllers\Consumer');

            Route::group([
                'prefix' => 'use',
            ], function () {
                Route::post('offers/{id}', [
                    'uses' => 'App\API\v1_0\LoyaltyCard\Controllers\Offer@useOffer'
                ]);

                // Route::post('vouchers/{id}', [
                //     'uses' => 'App\API\v1_0\LoyaltyCard\Controllers\Consumer@update'
                // ]);
            });
        });
        // Other modules
    });

    // Marketing Tool
    Route::group([
        'before' => [''],
        'prefix' => 'mt'
    ], function () {
        Route::resource('campaigns', 'App\MarketingTool\Controllers\Campaign', [
            'names' => [
                'index'     => 'mt.campaigns.index',
                'create'    => 'mt.campaigns.create',
                'edit'      => 'mt.campaigns.edit',
                'store'     => 'mt.campaigns.store',
                'update'    => 'mt.campaigns.update',
            ]
        ]);
        Route::post('campaigns/statistics', 'App\MarketingTool\Controllers\Campaign@statistics');
        Route::post('campaigns/duplication', 'App\MarketingTool\Controllers\Campaign@duplication');
        Route::post('campaigns/sendIndividual', 'App\MarketingTool\Controllers\Campaign@sendIndividual');
        Route::post('campaigns/sendGroup', 'App\MarketingTool\Controllers\Campaign@sendGroup');
        // Route::get('campaigns/automation', 'App\MarketingTool\Controllers\Campaign@automation');

        Route::resource('sms', 'App\MarketingTool\Controllers\Sms', [
            'names' => [
                'index'     => 'mt.sms.index',
                'create'    => 'mt.sms.create',
                'edit'      => 'mt.sms.edit',
                'store'     => 'mt.sms.store',
                'update'    => 'mt.sms.update',
            ]
        ]);
        Route::post('sms/sendIndividual', 'App\MarketingTool\Controllers\Sms@sendIndividual');
        Route::post('sms/sendGroup', 'App\MarketingTool\Controllers\Sms@sendGroup');
        // Route::get('sms/automation', 'App\MarketingTool\Controllers\Campaign@automation');

        Route::resource('templates', 'App\MarketingTool\Controllers\Template', [
            'names' => [
                'index'     => 'mt.templates.index',
                'create'    => 'mt.templates.create',
                'edit'      => 'mt.templates.edit',
                'store'     => 'mt.templates.store',
                'update'    => 'mt.templates.update',
            ]
        ]);
        Route::post('templates/load', 'App\MarketingTool\Controllers\Template@load');

        Route::resource('settings', 'App\MarketingTool\Controllers\Setting', [
            'names' => [
                'index'     => 'mt.settings.index',
                'create'    => 'mt.settings.create',
                'edit'      => 'mt.settings.edit',
                'store'     => 'mt.settings.store',
                'update'    => 'mt.settings.update',
            ]
        ]);

        Route::resource('groups', 'App\MarketingTool\Controllers\Group', [
            'names' => [
                'index'     => 'mt.groups.index',
                //'create'    => 'mt.groups.create',
                'edit'      => 'mt.groups.edit',
                'store'     => 'mt.groups.store',
                'update'    => 'mt.groups.update',
            ]
        ]);
        Route::post('groups/create', 'App\MarketingTool\Controllers\Group@create');

        Route::resource('consumers', 'App\MarketingTool\Controllers\Consumer', [
            'names' => [
                'index'     => 'mt.consumers.index',
                'show'      => 'mt.consumers.show',
            ]
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
