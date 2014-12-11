<?php
/*
|--------------------------------------------------------------------------
| User routes
|--------------------------------------------------------------------------
*/
Route::group([
    'before' => ['auth']
], function () {

    Route::get('profile', [
        'as' => 'user.profile',
        'uses' => 'App\Core\Controllers\User@profile'
    ]);

    Route::post('profile', [
        'uses' => 'App\Core\Controllers\User@updateProfile'
    ]);

    Route::get('dashboard', [
        'as' => 'dashboard.index',
        'before' => ['only.business'],
        'uses' => 'App\Core\Controllers\Dashboard@index'
    ]);

    Route::group([
        'prefix' => 'services'
    ], function () {

        Route::get('restaurant-booking', [
            'as'     => 'restaurant.index',
            'before' => ['premium.modules:restaurant', 'only.business'],
            'uses'   => 'App\Core\Controllers\Services@restaurant'
        ]);
    });
});
