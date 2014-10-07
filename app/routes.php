<?php

Route::pattern('id', '[0-9]+');
Route::pattern('slug', '[a-z0-9-]+');

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

/*
|--------------------------------------------------------------------------
| Front pages routes
|--------------------------------------------------------------------------
*/
Route::group([], function () {
    Route::get('/', [
        'as'    => 'home',
        'uses'  => 'App\Core\Controllers\Front@home'
    ]);

    Route::get('about', [
        'as' => 'front.about',
        'uses' => 'App\Core\Controllers\Front@about',
    ]);

    Route::get('contact', [
        'as' => 'front.contact',
        'uses' => 'App\Core\Controllers\Front@about',
    ]);

    Route::get('partners', [
        'as' => 'front.partners',
        'uses' => 'App\Core\Controllers\Front@partners',
    ]);

    Route::get('resellers', [
        'as' => 'front.resellers',
        'uses' => 'App\Core\Controllers\Front@resellers',
    ]);

    Route::get('media-companies', [
        'as' => 'front.media',
        'uses' => 'App\Core\Controllers\Front@media',
    ]);

    Route::get('directories', [
        'as' => 'front.directories',
        'uses' => 'App\Core\Controllers\Front@directories',
    ]);

    Route::group(['prefix' => 'business'], function () {
        Route::get('/', [
            'as'    => 'intro-business',
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
});

// JS localization
Route::get('jslocale.json', [
    'as'    => 'ajax.jslocale',
    'uses'  => 'App\Core\Controllers\Ajax\JsLocale@getJsLocale'
]);

/*
|--------------------------------------------------------------------------
| Search Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'search'], function () {
    Route::get('/', [
        'as'    => 'search',
        'uses'  => 'App\Core\Controllers\Search@index'
    ]);

    Route::get('services.json', [
        'as'    => 'ajax.services',
        'uses'  => 'App\Core\Controllers\Ajax\Search@getServices'
    ]);

    Route::get('locations.json', [
        'as'    => 'ajax.locations',
        'uses'  => 'App\Core\Controllers\Ajax\Search@getLocations'
    ]);

    Route::get('business/{id}', [
        'as'    => 'ajax.showBusiness',
        'uses'  => 'App\Core\Controllers\Ajax\Search@showBusiness'
    ]);
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'consumer'], function () {

    Route::get('register', [
        'as' => 'consumer.auth.register',
        'uses' => 'App\Core\Controllers\ConsumerAuth@register'
    ]);

    Route::post('register', [
        'uses' => 'App\Core\Controllers\ConsumerAuth@doRegister'
    ]);

});

Route::group(['prefix' => 'business'], function () {

    Route::get('register', [
        'as' => 'auth.register',
        'uses' => 'App\Core\Controllers\Auth@register'
    ]);

    Route::post('register', [
        'uses' => 'App\Core\Controllers\Auth@doRegister'
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
| Single business
|--------------------------------------------------------------------------
*/
Route::get('business/{id}/{slug?}', [
    'as'    => 'business.index',
    'uses'  => 'App\Core\Controllers\Ajax\Search@showBusiness'
]);
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
| Consumer hub routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'consumer-hub',
    'before' => ['auth']
], function () {
    \App\Consumers\Controllers\Hub::crudRoutes(
        '/',
        'consumer-hub'
    );

    Route::get('history', [
        'as' => 'consumer-hub.history',
        'uses' => 'App\Consumers\Controllers\Hub@getHistory',
    ]);

    Route::get('/{tab?}', [
        'as' => 'consumer-hub',
        'uses' => 'App\Consumers\Controllers\Hub@upsertHandler',
    ]);
});

/*
|--------------------------------------------------------------------------
| App routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'auth-lc'], function () {
    Route::get('login', [
        'as' => 'app.lc.login',
        'uses' => 'App\Core\Controllers\Auth@appLogin'
    ]);

    Route::post('login', [
        'uses' => 'App\Core\Controllers\Auth@doAppLogin'
    ]);

    Route::get('logout', [
        'as' => 'app.lc.logout',
        'uses' => 'App\Core\Controllers\Auth@appLogout'
    ]);
});

Route::group([
    'prefix' => 'app',
    'before' => ['auth-lc'],
], function () {
    Route::group([
        'prefix' => 'lc',
    ], function () {
        Route::get('/', [
            'as' => 'app.lc.index',
            'uses' => 'App\LoyaltyCard\Controllers\Consumer@appIndex',
        ]);

        Route::get('consumers/{id}', [
            'as' => 'app.lc.show',
            'uses' => 'App\LoyaltyCard\Controllers\Consumer@show',
        ]);

        Route::post('consumers', [
            'as' => 'app.lc.store',
            'uses' => 'App\LoyaltyCard\Controllers\Consumer@store',
        ]);

        Route::put('consumers/{id}', [
            'as' => 'app.lc.update',
            'uses' => 'App\LoyaltyCard\Controllers\Consumer@update',
        ]);
    });
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
        // Other modules
    });
});

/*
|-------------------------------------------------------------------------------
| Embed routes
|-------------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'embed'
], function () {

    // Appointment scheduler
    Route::group([
        'prefix' => 'as'
    ], function () {

        Route::post('bookings/add-front-booking-service', [
            'as' => 'as.bookings.service.front.add',
            'uses' => 'App\Appointment\Controllers\FrontBookings@addBookingService'
        ]);

        Route::post('bookings/add-front-end-booking', [
            'as' => 'as.bookings.frontend.add',
            'uses' => 'App\Appointment\Controllers\FrontBookings@addFrontEndBooking'
        ]);

        Route::post('bookings/remove-booking-service-in-cart', [
            'as' => 'as.bookings.service.remove.in.cart',
            'uses' => 'App\Appointment\Controllers\FrontBookings@removeBookingServiceInCart'
        ]);

        Route::post('employees', [
            'as' => 'as.embed.employees',
            'uses' => 'App\Appointment\Controllers\Embed\Base@getEmployees'
        ]);

        Route::post('timetable', [
            'as' => 'as.embed.timetable',
            'uses' => 'App\Appointment\Controllers\Embed\Base@getTimetable'
        ]);

        //----------------------------------------------------------------------
        // Layout 1
        //----------------------------------------------------------------------
        Route::get('get-extra-service-form', [
            'as' => 'as.embed.extra.form',
            'uses' => 'App\Appointment\Controllers\Embed\Layout1@getExtraServiceForm'
        ]);

        Route::post('add-confirm-info', [
            'as' => 'as.embed.confirm',
            'uses' => 'App\Appointment\Controllers\Embed\Layout1@addConfirmInfo'
        ]);

        //----------------------------------------------------------------------
        // Layout 2
        //----------------------------------------------------------------------
        Route::post('l2/timetable', [
            'as' => 'as.embed.l2.timetable',
            'uses' => 'App\Appointment\Controllers\Embed\Layout2@getTimetable'
        ]);

        Route::get('l2/checkout', [
            'as' => 'as.embed.l2.checkout',
            'uses' => 'App\Appointment\Controllers\Embed\Layout2@checkout'
        ]);

        Route::post('l2/checkout/confirm', [
            'as' => 'as.embed.l2.confirm',
            'uses' => 'App\Appointment\Controllers\Embed\Layout2@confirm'
        ]);

        //----------------------------------------------------------------------
        // Layout 3
        //----------------------------------------------------------------------
        Route::post('l3/checkout', [
            'as' => 'as.embed.checkout',
            'uses' => 'App\Appointment\Controllers\Embed\Layout3@checkout'
        ]);

        Route::post('l3/checkout/confirm', [
            'as'   => 'as.embed.checkout.confirm',
            'uses' => 'App\Appointment\Controllers\Embed\Layout3@confirm'
        ]);

        Route::get('{hash}', [
            'as' => 'as.embed.embed',
            'uses' => 'App\Appointment\Controllers\Embed\Base@index'
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| Cancel routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'cancel',
], function () {
    Route::get('as/{uuid}', [
        'as' => 'as.bookings.cancel',
        'uses' => 'App\Appointment\Controllers\Bookings@cancel'
    ]);

    Route::get('as/do/{uuid}', [
        'as' => 'as.bookings.doCancel',
        'uses' => 'App\Appointment\Controllers\Bookings@doCancel'
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

    Route::get('services/custom-time/{id}', [
        'as' => 'as.services.customTime',
        'uses' => 'App\Appointment\Controllers\Services@customTime'
    ]);

    Route::get('services/custom-time/{id}/upsert/{customTimeId?}', [
        'as'   => 'as.services.customTime.upsert',
        'uses' => 'App\Appointment\Controllers\Services@upsertCustomTime'
    ]);

    Route::post('services/custom-time/{id}/upsert/{customTimeId?}', [
        'uses' => 'App\Appointment\Controllers\Services@doUpsertCustomTime'
    ]);

    Route::get('services/custom-time/{id}/delete/{customTimeId?}', [
        'as'   => 'as.services.customTime.delete',
        'uses' => 'App\Appointment\Controllers\Services@deleteCustomTime'
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
        'uses' => 'App\Appointment\Controllers\Stat@index'
    ]);

    Route::get('bookings/statistics/calendar', [
        'as' => 'as.bookings.statistics.calendar',
        'uses' => 'App\Appointment\Controllers\Stat@calendar'
    ]);

    Route::get('bookings/statistics/monthly', [
        'as' => 'as.bookings.statistics.monthly',
        'uses' => 'App\Appointment\Controllers\Stat@monthly'
    ]);

    Route::post('bookings/cut', [
        'as' => 'as.bookings.cut',
        'uses' => 'App\Appointment\Controllers\Ajax\Bookings@cut'
    ]);

    Route::post('bookings/discard-cut', [
        'as' => 'as.bookings.discard-cut',
        'uses' => 'App\Appointment\Controllers\Ajax\Bookings@discardCut'
    ]);

    Route::post('bookings/paste', [
        'as' => 'as.bookings.paste',
        'uses' => 'App\Appointment\Controllers\Ajax\Bookings@paste'
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

    Route::get('employees/work-shift', [
        'as' => 'as.employees.customTime',
        'uses' => 'App\Appointment\Controllers\Employees@customTime'
    ]);

    Route::get('employees/work-shift/upsert/{customTimeId?}', [
        'as'   => 'as.employees.customTime.upsert',
        'uses' => 'App\Appointment\Controllers\Employees@upsertCustomTime'
    ]);

    Route::post('employees/work-shift/upsert/{customTimeId?}', [
        'uses' => 'App\Appointment\Controllers\Employees@doUpsertCustomTime'
    ]);

    Route::get('employees/work-shift/delete/{customTimeId?}', [
        'as'   => 'as.employees.customTime.delete',
        'uses' => 'App\Appointment\Controllers\Employees@deleteCustomTime'
    ]);

    Route::get('employees/work-shift-planning/{employeedId?}/{date?}', [
        'as' => 'as.employees.employeeCustomTime',
        'uses' => 'App\Appointment\Controllers\Employees@employeeCustomTime'
    ]);

    Route::post('employees/work-shift-planning/{employeedId?}/{date?}', [
        'as' => 'as.employees.employeeCustomTime.upsert',
        'uses' => 'App\Appointment\Controllers\Employees@upsertEmployeeCustomTime'
    ]);

    Route::post('employees/update-work-shift-plan/{employeedId}', [
        'as' => 'as.employees.employeeCustomTime.massiveUpdate',
        'uses' => 'App\Appointment\Controllers\Employees@massiveUpdateEmployeeCustomTime'
    ]);

    Route::post('employees/employee-delete-work-shift/{employeedId}', [
        'as' => 'as.employees.employeeCustomTime.delete',
        'uses' => 'App\Appointment\Controllers\Employees@deleteEmployeeCustomTime'
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

    Route::get('bookings/get-add-extra-service-form', [
        'as' => 'as.bookings.extra-service-form',
        'uses' => 'App\Appointment\Controllers\Bookings@getAddExtraServiceForm'
    ]);

    Route::post('bookings/add-extra-services', [
        'as' => 'as.bookings.add-extra-services',
        'uses' => 'App\Appointment\Controllers\Bookings@addExtraServices'
    ]);

    Route::post('bookings/remove-extra-service', [
        'as' => 'as.bookings.remove-extra-service',
        'uses' => 'App\Appointment\Controllers\Bookings@removeExtraService'
    ]);

    Route::get('bookings/get-change-status-form', [
        'as' => 'as.bookings.change-status-form',
        'uses' => 'App\Appointment\Controllers\Bookings@getChangeStatusForm'
    ]);

    Route::post('bookings/change-status', [
        'as' => 'as.bookings.change-status',
        'uses' => 'App\Appointment\Controllers\Bookings@changeStatus'
    ]);

    Route::get('bookings/modify-booking', [
        'as' => 'as.bookings.modify-form',
        'uses' => 'App\Appointment\Controllers\Bookings@getModifyBookingForm'
    ]);

    Route::post('bookings/modify-booking', [
        'as' => 'as.bookings.modify-form',
        'uses' => 'App\Appointment\Controllers\Bookings@doModifyBooking'
    ]);

    Route::get('bookings/search-consumer', [
        'as' => 'as.bookings.search-consumer',
        'uses' => 'App\Appointment\Controllers\Bookings@searchConsumer'
    ]);

    Route::get('bookings/ajax/get-employee-services', [
        'as' => 'as.bookings.employee.services',
        'uses' => 'App\Appointment\Controllers\Ajax\Bookings@getEmployeeServicesByCategory'
    ]);

    Route::get('bookings/ajax/get-service-times', [
        'as' => 'as.bookings.service.times',
        'uses' => 'App\Appointment\Controllers\Ajax\Bookings@getServiceTimes'
    ]);

    Route::post('bookings/add-booking-service', [
        'as' => 'as.bookings.service.add',
        'uses' => 'App\Appointment\Controllers\Bookings@addBookingService'
    ]);

    Route::post('bookings/add-booking', [
        'as' => 'as.bookings.add',
        'uses' => 'App\Appointment\Controllers\Bookings@upsertBooking'
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
|--------------------------------------------------------------------------
| Loyalty Card routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'loyalty-card',
    'before' => ['auth']
], function () {

    \App\LoyaltyCard\Controllers\Consumer::crudRoutes(
        'consumers',
        'lc.consumers'
    );

    \App\LoyaltyCard\Controllers\Offer::crudRoutes(
        'offers',
        'lc.offers'
    );

    \App\LoyaltyCard\Controllers\Voucher::crudRoutes(
        'vouchers',
        'lc.vouchers'
    );
});

/*
|--------------------------------------------------------------------------
| Marketing Tool routes
|--------------------------------------------------------------------------
*/
Route::group([
    'before' => ['auth'],
    'prefix' => 'marketing-tools'
], function () {
    Route::resource('campaigns', 'App\MarketingTool\Controllers\Campaign', [
        'names' => [
            'index'     => 'mt.campaigns.index',
            'create'    => 'mt.campaigns.create',
            'edit'      => 'mt.campaigns.edit',
            'store'     => 'mt.campaigns.store',
            'update'    => 'mt.campaigns.update',
            'destroy'   => 'mt.campaigns.delete',
        ]
    ]);
    Route::post('campaigns/statistics', [
        'as'   => 'mt.campaigns.statistics',
        'uses' => 'App\MarketingTool\Controllers\Campaign@statistics'
    ]);
    Route::post('campaigns/duplication', [
        'as'   => 'mt.campaigns.duplication',
        'uses' => 'App\MarketingTool\Controllers\Campaign@duplication'
    ]);
    Route::post('campaigns/sendIndividual', [
        'as'   => 'mt.campaigns.sendIndividual',
        'uses' => 'App\MarketingTool\Controllers\Campaign@sendIndividual'
    ]);
    Route::post('campaigns/sendGroup', [
        'as'   => 'mt.campaigns.group',
        'uses' => 'App\MarketingTool\Controllers\Campaign@sendGroup'
    ]);

    Route::resource('sms', 'App\MarketingTool\Controllers\Sms', [
        'names' => [
            'index'     => 'mt.sms.index',
            'create'    => 'mt.sms.create',
            'edit'      => 'mt.sms.edit',
            'store'     => 'mt.sms.store',
            'update'    => 'mt.sms.update',
            'destroy'   => 'mt.sms.delete',
        ]
    ]);
    Route::post('sms/sendIndividual', [
        'as'   => 'mt.sms.sendIndividual',
        'uses' => 'App\MarketingTool\Controllers\Sms@sendIndividual'
    ]);
    Route::post('sms/sendGroup', [
        'as'   => 'mt.sms.group',
        'uses' => 'App\MarketingTool\Controllers\Sms@sendGroup'
    ]);

    Route::resource('templates', 'App\MarketingTool\Controllers\Template', [
        'names' => [
            'index'     => 'mt.templates.index',
            'create'    => 'mt.templates.create',
            'edit'      => 'mt.templates.edit',
            'store'     => 'mt.templates.store',
            'update'    => 'mt.templates.update',
            'destroy'   => 'mt.templates.delete',
        ]
    ]);
    Route::post('templates/load', [
        'as'   => 'mt.templates.load',
        'uses' => 'App\MarketingTool\Controllers\Template@load'
    ]);

    Route::resource('settings', 'App\MarketingTool\Controllers\Setting', [
        'names' => [
            'index'     => 'mt.settings.index',
            'create'    => 'mt.settings.create',
            'edit'      => 'mt.settings.edit',
            'store'     => 'mt.settings.store',
            'update'    => 'mt.settings.update',
            'destroy'   => 'mt.settings.delete',
        ]
    ]);

    Route::resource('groups', 'App\MarketingTool\Controllers\Group', [
        'names' => [
            'index'     => 'mt.groups.index',
            'edit'      => 'mt.groups.edit',
            'store'     => 'mt.groups.store',
            'update'    => 'mt.groups.update',
            'destroy'   => 'mt.groups.delete',
        ]
    ]);
    Route::post('groups/create', [
        'as'   => 'mt.groups.create',
        'uses' => 'App\MarketingTool\Controllers\Group@create'
    ]);

    Route::resource('consumers', 'App\MarketingTool\Controllers\Consumer', [
        'names' => [
            'index'     => 'mt.consumers.index',
            'show'      => 'mt.consumers.show',
        ]
    ]);
});

/*
|--------------------------------------------------------------------------
| Module Images routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'images',
    'before' => ['auth']
], function () {

    Route::post('upload', [
        'as' => 'images.upload',
        'uses' => 'App\Core\Controllers\Images@upload'
    ]);

    Route::get('delete/{id}', [
        'as' => 'images.delete',
        'uses' => 'App\Core\Controllers\Images@delete'
    ]);

});

/*
|--------------------------------------------------------------------------
| Module Images routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'flash-deal',
    'before' => ['auth']
], function () {

    // Services
    \App\FlashDeal\Controllers\Services::crudRoutes(
        'services',
        'fd.services'
    );

    // Coupons
    \App\FlashDeal\Controllers\Coupons::crudRoutes(
        'coupons',
        'fd.coupons'
    );

    // Flash deals
    \App\FlashDeal\Controllers\FlashDeals::crudRoutes(
        'flash-deals',
        'fd.flash_deals'
    );

    \App\FlashDeal\Controllers\FlashDealDates::crudRoutes(
        'flash-deal-dates',
        'fd.flash_deal_dates'
    );

    Route::get('/{tab?}', [
        'as' => 'fd.index',
        'uses' => 'App\FlashDeal\Controllers\Index@index'
    ]);
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

    App\Core\Controllers\Admin\Users::crudRoutes('users', 'admin.users');

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

});
