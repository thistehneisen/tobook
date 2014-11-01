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

    App\Core\Controllers\Admin\Users::crudRoutes('users', 'admin.users');

    Route::get('users/{id}/login', [
        'as' => 'admin.users.login',
        'uses' => 'App\Core\Controllers\Admin\Users@stealSession'
    ]);

    //--------------------------------------------------------------------------
    // Premium modules
    //--------------------------------------------------------------------------
    Route::get('users/{id}/modules', [
        'as' => 'admin.users.modules',
        'uses' => 'App\Core\Controllers\Admin\Users@modules'
    ]);

    Route::post('users/{id}/modules', [
        'uses' => 'App\Core\Controllers\Admin\Users@enableModule'
    ]);

    Route::get('users/{userId}/modules/{id}/activation/', [
        'as'   => 'admin.users.modules.activation',
        'uses' => 'App\Core\Controllers\Admin\Users@toggleActivation'
    ]);

});
