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

require app_path().'/routes/home.php';
require app_path().'/routes/search.php';
require app_path().'/routes/auth.php';
require app_path().'/routes/business.php';
require app_path().'/routes/user.php';
require app_path().'/routes/embed.php';
require app_path().'/routes/cart.php';
require app_path().'/routes/image.php';
require app_path().'/routes/admin.php';
//------------------------------------------------------------------------------
// Modules
//------------------------------------------------------------------------------
require app_path().'/routes/api.php';
require app_path().'/routes/consumer_hub.php';
require app_path().'/routes/appointment.php';
require app_path().'/routes/loyalty_card.php';
require app_path().'/routes/flash_deal.php';

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
