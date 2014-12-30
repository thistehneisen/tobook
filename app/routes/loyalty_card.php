<?php
/*
|--------------------------------------------------------------------------
| Loyalty Card routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'loyalty-card',
    'before' => ['auth', 'only.business', 'premium.modules:loyalty']
], function () {
    \App\LoyaltyCard\Controllers\Offer::crudRoutes(
        'offers',
        'lc.offers'
    );

    \App\LoyaltyCard\Controllers\Voucher::crudRoutes(
        'vouchers',
        'lc.vouchers'
    );
});

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
        \App\LoyaltyCard\Controllers\LoyaltyApp::crudRoutes(
            'consumers',
            'lc.consumers'
        );

        Route::get('/', [
            'as' => 'app.lc.index',
            'uses' => 'App\LoyaltyCard\Controllers\LoyaltyApp@index',
        ]);

        Route::get('consumers/{id}', [
            'as' => 'app.lc.show',
            'uses' => 'App\LoyaltyCard\Controllers\LoyaltyApp@show',
        ]);

        Route::post('consumers', [
            'as' => 'app.lc.store',
            'uses' => 'App\LoyaltyCard\Controllers\LoyaltyApp@store',
        ]);

        Route::put('consumers/{id}', [
            'as' => 'app.lc.update',
            'uses' => 'App\LoyaltyCard\Controllers\LoyaltyApp@update',
        ]);
    });
});
