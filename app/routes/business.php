<?php
/*
|--------------------------------------------------------------------------
| Business category
|--------------------------------------------------------------------------
*/
Route::get('search/categories/{id}-{slug?}', [
    'as'    => 'business.master_category',
    'uses'  => 'App\Core\Controllers\Front@businessList'
]);

Route::get('search/treatments/{id}-{slug?}', [
    'as'    => 'business.treatment',
    'uses'  => 'App\Core\Controllers\Front@businessList'
]);

// Route::get('/test/{id}-{slug?}', [
//     'as'    => 'business.test',
//     'uses'  => 'App\Core\Controllers\Front@test'
// ]);
    
Route::get('/search', [
    'as'    => 'business.search',
    'uses'  => 'App\Core\Controllers\Front@search'
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

    Route::get('/review/{id}-{slug?}', [
        'as'    => 'businesses.review',
        'uses'  => 'App\Core\Controllers\Front@review'
    ]);

    Route::post('/review/{id}-{slug?}', [
        'as'    => 'businesses.doReview',
        'uses'  => 'App\Core\Controllers\Front@doReview'
    ]);

    //--------------------------------------------------------------------------
    // CP booking form
    //--------------------------------------------------------------------------
    Route::get('booking/services', [
        'as'    => 'business.booking.services',
        'uses'  => 'App\Appointment\Controllers\Embed\LayoutCp@getServices'
    ]);

    Route::get('booking/timetable', [
        'as'    => 'business.booking.timetable',
        'uses'  => 'App\Appointment\Controllers\Embed\LayoutCp@getTimetable'
    ]);

    Route::get('booking/payments', [
        'as'    => 'business.booking.payments',
        'uses'  => 'App\Appointment\Controllers\Embed\LayoutCp@getPaymentOptions'
    ]);

    Route::get('booking/employees', [
        'as'    => 'business.booking.employees',
        'uses'  => 'App\Appointment\Controllers\Embed\LayoutCp@getEmployees'
    ]);

    Route::post('booking/pay_at_venue', [
        'as'    => 'business.booking.pay_at_venue',
        'uses'  => 'App\Appointment\Controllers\Embed\LayoutCp@payAtVenue'
    ]);

    Route::post('booking/validate_coupon', [
        'as'    => 'business.booking.validate.coupon',
        'uses'  => 'App\Appointment\Controllers\Embed\LayoutCp@validateCoupon'
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
    // Single business
    //--------------------------------------------------------------------------
    Route::get('{id}-{slug?}', [
        'as'    => 'business.index',
        'uses'  => 'App\Core\Controllers\Ajax\Search@showBusiness'
    ]);
});
