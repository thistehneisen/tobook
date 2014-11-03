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
    // Users
    //--------------------------------------------------------------------------
    App\Core\Controllers\Admin\Users::crudRoutes('users', 'admin.users');
    Route::group(['prefix' => 'users'], function () {

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
        Route::get('{id}/modules', [
            'as' => 'admin.users.modules',
            'uses' => 'App\Core\Controllers\Admin\Users@modules'
        ]);

        Route::post('{id}/modules', [
            'uses' => 'App\Core\Controllers\Admin\Users@enableModule'
        ]);

        Route::get('{userId}/modules/{id}/activation/', [
            'as'   => 'admin.users.modules.activation',
            'uses' => 'App\Core\Controllers\Admin\Users@toggleActivation'
        ]);

    });

});
