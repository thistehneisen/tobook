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

    });

    //--------------------------------------------------------------------------
    // Statistics
    //--------------------------------------------------------------------------

    Route::group(['prefix' => 'stats'], function () {
        Route::get('flash-deals', [
            'as' => 'admin.stats.fd',
            'uses' => 'App\Core\Controllers\Admin\Stats\FlashDeals@index'
        ]);
    });

    //--------------------------------------------------------------------------
    // Settings
    //--------------------------------------------------------------------------
    Route::group(['prefix' => 'settings'], function() {
        Route::get('/', [
            'as' => 'admin.settings',
            'uses' => 'App\Core\Controllers\Admin\Settings@index'
        ]);
    });
});
