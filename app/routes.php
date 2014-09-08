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
    'uses'  => 'App\Core\Controllers\Front@home'
]);

Route::get('/search', [
    'as'    => 'search',
    'uses'  => 'App\Core\Controllers\Front@search'
]);

Route::group(['prefix' => 'business'], function () {
    Route::get('/', [
        'as'    => 'business-index',
        'uses'  => 'App\Core\Controllers\Front@businessIndex'
    ]);

    Route::get('website-list', [
        'as' => 'intro-website-list',
        'uses' => 'App\Core\Controllers\Front@businessWebsiteList',
    ]);

    Route::get('loyalty', [
        'as' => 'intro-loyalty',
        'uses' => 'App\Core\Controllers\Front@businessLoyalty',
    ]);

    Route::get('online-booking', [
        'as'    => 'intro-online-booking',
        'uses'  => 'App\Core\Controllers\Front@businessOnlineBooking',
    ]);

    Route::get('customer-registration', [
        'as' => 'intro-customer-registration',
        'uses' => 'App\Core\Controllers\Front@businessMarketingTools',
    ]);

    Route::get('cashier', [
        'as' => 'intro-cashier',
        'uses' => 'App\Core\Controllers\Front@businessCashier',
    ]);

    Route::get('marketing-tools', [
        'as' => 'intro-marketing-tools',
        'uses' => 'App\Core\Controllers\Front@businessMarketingTools',
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
        'uses' => 'App\Core\Controllers\User@updateProfile'
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
| Appointment Scheduler routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'appointment-scheduler',
    'before' => ['auth']
], function () {

    Route::get('/employee/{id}/{date?}', [
        'as' => 'as.employee',
        'uses' => 'App\Appointment\Controllers\Index@employee'
    ]);

    Route::get('/employees/get-freetime-form', [
        'as' => 'as.employees.freetime.form',
        'uses' => 'App\Appointment\Controllers\Employees@getFreetimeForm'
    ]);

    Route::post('/employees/add-employee-freetime', [
        'as' => 'as.employees.freetime.add',
        'uses' => 'App\Appointment\Controllers\Employees@addEmployeeFreeTime'
    ]);

    Route::post('/employees/delete-employee-freetime', [
        'as' => 'as.employees.freetime.delete',
        'uses' => 'App\Appointment\Controllers\Employees@deleteEmployeeFreeTime'
    ]);

    Route::get('services', [
        'as' => 'as.services.index',
        'uses' => 'App\Appointment\Controllers\Services@index'
    ]);

    Route::get('services/create', [
        'as' => 'as.services.create',
        'uses' => 'App\Appointment\Controllers\Services@create'
    ]);

    Route::post('services/create', [
        'as' => 'as.services.create',
        'uses' => 'App\Appointment\Controllers\Services@doCreate'
    ]);

    Route::get('services/edit', [
        'as' => 'as.services.edit',
        'uses' => 'App\Appointment\Controllers\Services@edit'
    ]);

    Route::post('services/edit', [
        'as' => 'as.services.edit',
        'uses' => 'App\Appointment\Controllers\Services@doEdit'
    ]);

    Route::get('services/delete', [
        'as' => 'as.services.delete',
        'uses' => 'App\Appointment\Controllers\Services@delete'
    ]);

    Route::post('services/destroy', [
        'as' => 'as.services.destroy',
        'uses' => 'App\Appointment\Controllers\Services@destroy'
    ]);

    // Bookings
    \App\Appointment\Controllers\Bookings::crudRoutes(
        'bookings',
        'as.bookings'
    );

    Route::get('bookings/invoices', [
        'as' => 'as.bookings.invoices',
        'uses' => 'App\Appointment\Controllers\Bookings@invoices'
    ]);

    Route::get('bookings/customers', [
        'as' => 'as.bookings.customers',
        'uses' => 'App\Appointment\Controllers\Bookings@customers'
    ]);

    Route::get('bookings/statistics', [
        'as' => 'as.bookings.statistics',
        'uses' => 'App\Appointment\Controllers\Bookings@statistics'
    ]);

    // Service Category
    \App\Appointment\Controllers\Categories::crudRoutes(
        'services/categories',
        'as.services.categories'
    );

    // Service Resource
    \App\Appointment\Controllers\Resources::crudRoutes(
        'services/resources',
        'as.services.resources'
    );

    // Employee
    \App\Appointment\Controllers\Employees::crudRoutes(
        'employees',
        'as.employees'
    );

    // Service
    \App\Appointment\Controllers\Services::crudRoutes(
        'services',
        'as.services'
    );

    \App\Appointment\Controllers\ExtraServices::crudRoutes(
        'services/extras',
        'as.services.extras'
    );

    Route::get('services/employees/free-time', [
        'as' => 'as.employees.freetime',
        'uses' => 'App\Appointment\Controllers\Employees@freetime'
    ]);

    Route::get('employees/custom-time', [
        'as' => 'as.employees.customTime',
        'uses' => 'App\Appointment\Controllers\Employees@customTime'
    ]);

    Route::get('employees/default-time/{id}', [
        'as' => 'as.employees.defaultTime.get',
        'uses' => 'App\Appointment\Controllers\Employees@defaultTime'
    ]);

    Route::post('employees/default-time', [
        'as' => 'as.employees.defaultTime',
        'uses' => 'App\Appointment\Controllers\Employees@updateDefaultTime'
    ]);

    Route::get('bookings/get-booking-form', [
        'as' => 'as.bookings.form',
        'uses' => 'App\Appointment\Controllers\Bookings@getBookingForm'
    ]);

    Route::get('bookings/get-employee-services', [
        'as' => 'as.bookings.employee.services',
        'uses' => 'App\Appointment\Controllers\Bookings@getEmployeeServicesByCategory'
    ]);

    Route::get('bookings/get-service-times', [
        'as' => 'as.bookings.service.times',
        'uses' => 'App\Appointment\Controllers\Bookings@getServiceTimes'
    ]);

    Route::post('bookings/add-booking-service', [
        'as' => 'as.bookings.service.add',
        'uses' => 'App\Appointment\Controllers\Bookings@addBookingService'
    ]);

    Route::post('bookings/add-booking', [
        'as' => 'as.bookings.add',
        'uses' => 'App\Appointment\Controllers\Bookings@addBooking'
    ]);

    Route::post('bookings/remove-booking-service', [
        'as' => 'as.bookings.service.remove',
        'uses' => 'App\Appointment\Controllers\Bookings@removeBookingService'
    ]);

    Route::post('bookings/remove-booking-service-in-cart', [
        'as' => 'as.bookings.service.remove.in.cart',
        'uses' => 'App\Appointment\Controllers\Bookings@removeBookingServiceInCart'
    ]);

    // Embed
    Route::get('embed', [
        'as' => 'as.embed.index',
        'uses' => 'App\Appointment\Controllers\Embed@index'
    ]);

    Route::get('preview', [
        'as' => 'as.embed.preview',
        'uses' => 'App\Appointment\Controllers\Embed@preview'
    ]);

    // Options
    Route::get('options/working-time', [
        'uses' => 'App\Appointment\Controllers\Options@workingTime'
    ]);

    Route::post('options/working-time', [
        'uses' => 'App\Appointment\Controllers\Options@updateWorkingTime'
    ]);

    Route::get('options/{page?}', [
        'as'   => 'as.options',
        'uses' => 'App\Appointment\Controllers\Options@index'
    ]);

    Route::post('options/{page?}', [
        'uses' => 'App\Appointment\Controllers\Options@update'
    ]);

    // Report
    Route::get('reports', [
        'as' => 'as.reports.employees',
        'uses' => 'App\Appointment\Controllers\Reports@employees'
    ]);

    // Catch-all route should always be at the bottom
    Route::get('/{date?}', [
        'as' => 'as.index',
        'uses' => 'App\Appointment\Controllers\Index@index'
    ]);
});

/*
|-------------------------------------------------------------------------------
| Embed routes
|-------------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'embed'
], function() {

    // Appointment scheduler
    Route::get('appointment-scheduler/{hash}', [
        'as' => 'as.embed.embed',
        'uses' => 'App\Appointment\Controllers\Embed@embed'
    ]);

    Route::get('appointment-scheduler/get-extra-service-form', [
        'as' => 'as.embed.extra.form',
        'uses' => 'App\Appointment\Controllers\Embed@getExtraServiceForm'
    ]);
    // End appointment scheduler

    Route::post('bookings/add-front-booking-service', [
        'as' => 'as.bookings.service.front.add',
        'uses' => 'App\Appointment\Controllers\FrontBookings@addBookingService'
    ]);

    Route::post('bookings/add-front-end-booking', [
        'as' => 'as.bookings.frontend.add',
        'uses' => 'App\Appointment\Controllers\FrontBookings@addFrontEndBooking'
    ]);

    Route::post('bookings/add-confirm-info', [
        'as' => 'as.embed.confirm',
        'uses' => 'App\Appointment\Controllers\Embed@addConfirmInfo'
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
], function () {
    Route::group([
        'prefix' => 'v1.0',
    ], function () {
        Route::group([
            'prefix' => 'lc',
        ], function () {
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
