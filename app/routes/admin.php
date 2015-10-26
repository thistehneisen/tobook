<?php
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

    //--------------------------------------------------------------------------
    // Admin
    //--------------------------------------------------------------------------
    Route::get('admin/create', [
        'as'   => 'admin.create',
        'uses' => 'App\Core\Controllers\Admin\Admin@create'
    ]);

    Route::post('admin/create', [
        'uses' => 'App\Core\Controllers\Admin\Admin@doCreate'
    ]);

    //--------------------------------------------------------------------------
    // Users
    //--------------------------------------------------------------------------
    Route::group(['prefix' => 'coupon'], function () {

        Route::get('index', [
            'as' => 'admin.coupon.index',
            'uses' => 'App\Core\Controllers\Admin\Coupon@index'
        ]);

        Route::post('setting', [
            'as' => 'admin.coupon.setting',
            'uses' => 'App\Core\Controllers\Admin\Coupon@setting'
        ]);

        Route::get('campaigns', [
            'as' => 'admin.coupon.campaigns',
            'uses' => 'App\Core\Controllers\Admin\Coupon@campaigns'
        ]);

        Route::get('campaigns/create', [
            'as' => 'admin.coupon.create',
            'uses' => 'App\Core\Controllers\Admin\Coupon@create'
        ]);

        Route::post('campaigns/create', [
            'as' => 'admin.coupon.campaigns.create',
            'uses' => 'App\Core\Controllers\Admin\Coupon@doCreate'
        ]);

    });

    //--------------------------------------------------------------------------
    // Users
    //--------------------------------------------------------------------------
    App\Core\Controllers\Admin\Users::crudRoutes('users', 'admin.users');
    Route::group(['prefix' => 'users'], function () {

        //----------------------------------------------------------------------
        // Deleted users
        //----------------------------------------------------------------------
        Route::get('deleted', [
            'as' => 'admin.users.deleted',
            'uses' => 'App\Core\Controllers\Admin\Users@deleted'
        ]);

        //----------------------------------------------------------------------
        // Core
        //----------------------------------------------------------------------

        Route::post('{id}/business', [
            'as' => 'admin.users.business',
            'uses' => 'App\Core\Controllers\Admin\Users@updateBusiness'
        ]);

        Route::get('{id}/login', [
            'as' => 'admin.users.login',
            'uses' => 'App\Core\Controllers\Admin\Users@stealSession'
        ]);

        //----------------------------------------------------------------------
        // Premium modules
        //----------------------------------------------------------------------
        Route::post('{id}/modules', [
            'as' => 'admin.users.modules',
            'uses' => 'App\Core\Controllers\Admin\Users@enableModule'
        ]);

        Route::get('{userId}/modules/{id}/activation/', [
            'as'   => 'admin.users.modules.activation',
            'uses' => 'App\Core\Controllers\Admin\Users@toggleActivation'
        ]);

        //----------------------------------------------------------------------
        // Commissions
        //----------------------------------------------------------------------
        Route::get('{id}/commissions/{action}', [
            'as' => 'admin.users.commissions.show',
            'uses' => 'App\Core\Controllers\Admin\Commissions@show'
        ]);

        Route::post('{id}/commissions/{action}', [
            'uses' => 'App\Core\Controllers\Admin\Commissions@doAction'
        ]);

        Route::get('{id}/commissions', [
            'as' => 'admin.users.commissions',
            'uses' => 'App\Core\Controllers\Admin\Commissions@index'
        ]);

        //----------------------------------------------------------------------
        // Commissions Counter
        //----------------------------------------------------------------------
        Route::get('{id}/commissions-counter/{employee?}', [
            'as' => 'admin.users.commissions.counter',
            'uses' => 'App\Core\Controllers\Admin\Commissions@counter'
        ]);

        Route::get('{id}/commissions-counter/status/{booking}', [
            'as' => 'admin.users.commissions.status',
            'uses' => 'App\Core\Controllers\Admin\Commissions@status'
        ]);

        Route::post('{id}/commissions-counter/mass-status', [
            'as' => 'admin.users.commissions.mass_status',
            'uses' => 'App\Core\Controllers\Admin\Commissions@massStatus'
        ]);

        Route::get('{id}/commissions-pdf/{employee?}', [
            'as' => 'admin.users.commissions.pdf',
            'uses' => 'App\Core\Controllers\Admin\Commissions@pdf'
        ]);

        Route::post('{id}/commissions-send-report', [
            'as' => 'admin.users.commissions.send_report',
            'uses' => 'App\Core\Controllers\Admin\Commissions@sendReport'
        ]);
    });

    //--------------------------------------------------------------------------
    // Master categories
    //--------------------------------------------------------------------------
    App\Core\Controllers\Admin\MasterCategories::crudRoutes('master-cats', 'admin.master-cats');

    //--------------------------------------------------------------------------
    // Treatment types
    //--------------------------------------------------------------------------
    App\Core\Controllers\Admin\TreatmentTypes::crudRoutes('treatment-types', 'admin.treatment-types');

    //--------------------------------------------------------------------------
    // Keywords
    //--------------------------------------------------------------------------
    App\Core\Controllers\Admin\Keywords::crudRoutes('keywords', 'admin.keywords');


    //--------------------------------------------------------------------------
    // Settings
    //--------------------------------------------------------------------------
    Route::get('settings', [
        'as' => 'admin.settings',
        'uses' => 'App\Core\Controllers\Admin\Settings@index'
    ]);

    Route::post('settings', [
        'uses' => 'App\Core\Controllers\Admin\Settings@save'
    ]);

    Route::get('booking-terms', [
        'as' => 'admin.booking.terms',
        'uses' => 'App\Core\Controllers\Admin\Settings@bookingTerms'
    ]);

    Route::post('booking-terms', [
        'uses' => 'App\Core\Controllers\Admin\Settings@saveBookingTerms'
    ]);

    //--------------------------------------------------------------------------
    // Static Pages
    //--------------------------------------------------------------------------
    Route::get('pages', [
        'as'   => 'admin.pages',
        'uses' => 'App\Core\Controllers\Admin\StaticPages@index'
    ]);

    Route::post('pages', [
        'uses' => 'App\Core\Controllers\Admin\StaticPages@save'
    ]);

    //--------------------------------------------------------------------------
    // SEO
    //--------------------------------------------------------------------------
    Route::get('seo', [
        'as' => 'admin.seo',
        'uses' => 'App\Core\Controllers\Admin\SEO@index'
    ]);

    Route::post('seo', [
        'uses' => 'App\Core\Controllers\Admin\SEO@save'
    ]);

    //--------------------------------------------------------------------------
    // Statistics
    //--------------------------------------------------------------------------
    Route::get('statistics', [
        'as' => 'admin.statistics',
        'uses' => 'App\Core\Controllers\Admin\Statistics@index'
    ]);

    Route::post('statistics', [
        'uses' => 'App\Core\Controllers\Admin\Statistics@index'
    ]);
});
