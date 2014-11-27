<?php
/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'api',
    'before' => 'auth-api',
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
        Route::group([
            'prefix' => 'as',
        ], function () {
            Route::get('schedules', 'App\API\v1_0\Appointment\Controllers\Schedule@getSchedules');
            Route::resource('bookings', 'App\API\v1_0\Appointment\Controllers\Booking', [
                'only' => ['store', 'show', 'update', 'destroy']
            ]);
            Route::resource('consumers', 'App\API\v1_0\Appointment\Controllers\Consumer');
            Route::put('bookings/{id}/status', 'App\API\v1_0\Appointment\Controllers\Booking@putStatus');
            Route::put('bookings/{id}/modify_time', 'App\API\v1_0\Appointment\Controllers\Booking@putModifyTime');
            Route::put('bookings/{id}/schedule', 'App\API\v1_0\Appointment\Controllers\Booking@putSchedule');
            Route::get('services', 'App\API\v1_0\Appointment\Controllers\Service@getServices');
            Route::get('services/{id}', 'App\API\v1_0\Appointment\Controllers\Service@getService');
            Route::get('service-categories', 'App\API\v1_0\Appointment\Controllers\Service@getCategories');
            Route::get('service-categories/{id}', 'App\API\v1_0\Appointment\Controllers\Service@getCategory');
        });
        // Other modules
    });
});
