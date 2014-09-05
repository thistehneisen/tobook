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
            'as' => 'appointment.index',
            'uses' => 'App\Core\Controllers\Services@appointment'
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

    Route::group([
        'before' => [''], // Attach a filter to check payment
        'prefix' => 'modules'
    ], function () {

        // Loyalty Card
        Route::group(['prefix' => 'lc'], function () {
            Route::resource('consumers', 'App\LoyaltyCard\Controllers\Consumer');
        });

        // Other modules
    });
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

    // Embed
    Route::get('embed', [
        'as' => 'as.embed.index',
        'uses' => 'App\Appointment\Controllers\Embed@index'
    ]);

    Route::get('preview', [
        'as' => 'as.embed.preview',
        'uses' => 'App\Appointment\Controllers\Embed@preview'
    ]);

    Route::get('embed/{hash}/{date?}/{serviceId?}', [
        'as' => 'as.embed.embed',
        'uses' => 'App\Appointment\Controllers\Embed@embed'
    ]);

    Route::get('embed/get-extra-service-form/{hash}', [
        'as' => 'as.embed.extra.form',
        'uses' => 'App\Appointment\Controllers\Embed@getExtraServiceForm'
    ]);

    // Options
    Route::get('options/{page?}', [
        'as'   => 'as.options',
        'uses' => 'App\Appointment\Controllers\Options@index'
    ]);

    Route::post('options/{page?}', [
        'uses' => 'App\Appointment\Controllers\Options@update'
    ]);

    // Catch-all route should always be at the bottom
    Route::get('/{date?}', [
        'as' => 'as.index',
        'uses' => 'App\Appointment\Controllers\Index@index'
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

    // User model uses a different way to save data
    Route::post('users/{id}', [
        'uses' => 'App\Core\Controllers\Admin\Users@doEdit'
    ]);

    Route::get('users/login/{id}', [
        'as' => 'admin.users.login',
        'uses' => 'App\Core\Controllers\Admin\Users@stealSession'
    ]);

    // CRUD actions
    Route::get('{model}', [
        'as' => 'admin.crud.index',
        'uses' => 'App\Core\Controllers\Admin\Crud@index'
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
