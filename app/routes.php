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

    Route::get('/', [
        'as' => 'as.index',
        'uses' => 'App\Appointment\Controllers\Index@index'
    ]);

    Route::get('services', [
        'as' => 'as.services.index',
        'uses' => 'App\Appointment\Controllers\Services@index'
    ]);

    Route::get('services/create', [
        'as' => 'as.services.create',
        'uses' => 'App\Appointment\Controllers\Categories@create'
    ]);

    Route::get('services/categories', [
        'as' => 'as.services.categories',
        'uses' => 'App\Appointment\Controllers\Categories@categories'
    ]);

    Route::get('services/categories/datatable', [
        'as' => 'as.services.categories.datatable',
        'uses' => 'App\Appointment\Controllers\Categories@datatable'
    ]);

    Route::get('services/categories/create', [
        'as' => 'as.services.categories.create',
        'uses' => 'App\Appointment\Controllers\Categories@create'
    ]);

    Route::get('services/categories/edit/{id}', [
        'as' => 'as.services.categories.edit',
        'uses' => 'App\Appointment\Controllers\Categories@edit'
    ]);

    Route::get('services/categories/delete/{id}', [
        'as' => 'as.services.categories.delete',
        'uses' => 'App\Appointment\Controllers\Categories@delete'
    ]);

    Route::post('services/categories/destroy', [
        'as' => 'as.services.categories.destroy',
        'uses' => 'App\Appointment\Controllers\Categories@destroy'
    ]);

    Route::post('services/categories/create', [
        'as' => 'as.services.categories.create',
        'uses' => 'App\Appointment\Controllers\Categories@doCreate'
    ]);

    Route::post('services/categories/edit/{id}', [
        'as' => 'as.services.categories.edit',
        'uses' => 'App\Appointment\Controllers\Categories@doEdit'
    ]);

    Route::get('services/resources', [
        'as' => 'as.services.resources',
        'uses' => 'App\Appointment\Controllers\Resources@resources'
    ]);

    Route::get('services/resources/create', [
        'as' => 'as.services.resources.create',
        'uses' => 'App\Appointment\Controllers\Resources@create'
    ]);

    Route::post('services/resources/create', [
        'as' => 'as.services.resources.create',
        'uses' => 'App\Appointment\Controllers\Resources@doCreate'
    ]);

    Route::get('services/resources/edit/{id}', [
        'as' => 'as.services.resources.edit',
        'uses' => 'App\Appointment\Controllers\Resources@edit'
    ]);

    Route::post('services/resources/edit/{id}', [
        'as' => 'as.services.resources.edit',
        'uses' => 'App\Appointment\Controllers\Resources@doEdit'
    ]);

    Route::get('services/resources/delete/{id}', [
        'as' => 'as.services.resources.delete',
        'uses' => 'App\Appointment\Controllers\Resources@delete'
    ]);

    Route::post('services/resources/destroy', [
        'as' => 'as.services.resources.destroy',
        'uses' => 'App\Appointment\Controllers\Resources@destroy'
    ]);

    Route::get('services/extras', [
        'as' => 'as.services.extras',
        'uses' => 'App\Appointment\Controllers\ExtraServices@extras'
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
