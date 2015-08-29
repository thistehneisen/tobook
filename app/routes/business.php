<?php
/*
|--------------------------------------------------------------------------
| Business category
|--------------------------------------------------------------------------
*/
Route::get('categories/{id}-{slug?}', [
    'as'    => 'business.master_category',
    'uses'  => 'App\Core\Controllers\Front@category'
]);

Route::get('treatments/{id}-{slug?}', [
    'as'    => 'business.treatment',
    'uses'  => 'App\Core\Controllers\Front@category'
]);

/*
|--------------------------------------------------------------------------
| Single business
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'businesses'], function () {

    Route::get('/', [
        'as'    => 'businesses',
        'uses'  => 'App\Core\Controllers\Front@businesses'
    ]);

    Route::get('category/{id}-{slug?}', [
        'as'    => 'business.category',
        'uses'  => 'App\Core\Controllers\Front@category'
    ]);

    Route::post('{id}-{slug?}/contact', [
        'as'    => 'business.contact',
        'uses'  => 'App\Core\Controllers\Businesses@contact'
    ]);

    Route::post('{id}-{slug?}/request', [
        'as'    => 'business.request',
        'uses'  => 'App\Core\Controllers\Businesses@request'
    ]);

    //--------------------------------------------------------------------------
    // CP booking form
    //--------------------------------------------------------------------------
    Route::get('{hash}/booking/services', [
        'as'    => 'business.booking.services',
        'uses'  => 'App\Appointment\Controllers\Embed\LayoutCp@getServices'
    ]);

    Route::get('{hash}/booking/timetable', [
        'as'    => 'business.booking.timetable',
        'uses'  => 'App\Appointment\Controllers\Embed\LayoutCp@getTimetable'
    ]);

    Route::get('{hash}/booking/payments', [
        'as'    => 'business.booking.payments',
        'uses'  => 'App\Appointment\Controllers\Embed\LayoutCp@getPaymentOptions'
    ]);

    Route::get('{hash}/booking/employees', [
        'as'    => 'business.booking.employees',
        'uses'  => 'App\Appointment\Controllers\Embed\LayoutCp@getEmployees'
    ]);

    Route::post('{hash}/booking/pay_at_venue', [
        'as'    => 'business.booking.pay_at_venue',
        'uses'  => 'App\Appointment\Controllers\Embed\LayoutCp@payAtVenue'
    ]);

    //--------------------------------------------------------------------------
    // Single business
    //--------------------------------------------------------------------------
    Route::get('{id}-{slug?}', [
        'as'    => 'business.index',
        'uses'  => 'App\Core\Controllers\Ajax\Search@showBusiness'
    ]);
});
