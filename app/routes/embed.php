<?php
/*
|-------------------------------------------------------------------------------
| Embed routes
|-------------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'embed'
], function () {

    // Appointment scheduler
    Route::group([
        'prefix' => 'as'
    ], function () {

        Route::post('bookings/add-front-booking-service', [
            'as' => 'as.bookings.service.front.add',
            'uses' => 'App\Appointment\Controllers\FrontBookings@addBookingService'
        ]);

        Route::post('bookings/add-front-end-booking', [
            'as' => 'as.bookings.frontend.add',
            'uses' => 'App\Appointment\Controllers\FrontBookings@addFrontEndBooking'
        ]);

        Route::post('bookings/cart', [
            'as' => 'as.booking.frontend.cart',
            'uses' => 'App\Appointment\Controllers\FrontBookings@addBookingFromCart'
        ]);

        Route::post('bookings/remove-booking-service-in-cart', [
            'as' => 'as.bookings.service.remove.in.cart',
            'uses' => 'App\Appointment\Controllers\FrontBookings@removeBookingServiceInCart'
        ]);

        Route::post('employees', [
            'as' => 'as.embed.employees',
            'uses' => 'App\Appointment\Controllers\Embed\Base@getEmployees'
        ]);

        Route::post('timetable', [
            'as' => 'as.embed.timetable',
            'uses' => 'App\Appointment\Controllers\Embed\Base@getTimetable'
        ]);

        //----------------------------------------------------------------------
        // Layout 1
        //----------------------------------------------------------------------
        Route::get('get-extra-service-form', [
            'as' => 'as.embed.extra.form',
            'uses' => 'App\Appointment\Controllers\Embed\Layout1@getExtraServiceForm'
        ]);

        Route::post('add-confirm-info', [
            'as' => 'as.embed.confirm',
            'uses' => 'App\Appointment\Controllers\Embed\Layout1@addConfirmInfo'
        ]);

        //----------------------------------------------------------------------
        // Layout 2
        //----------------------------------------------------------------------
        Route::post('l2/timetable', [
            'as' => 'as.embed.l2.timetable',
            'uses' => 'App\Appointment\Controllers\Embed\Layout2@getTimetable'
        ]);

        Route::get('l2/checkout', [
            'as' => 'as.embed.l2.checkout',
            'uses' => 'App\Appointment\Controllers\Embed\Layout2@checkout'
        ]);

        Route::post('l2/checkout/confirm', [
            'as' => 'as.embed.l2.confirm',
            'uses' => 'App\Appointment\Controllers\Embed\Layout2@confirm'
        ]);

        //----------------------------------------------------------------------
        // Layout 3
        //----------------------------------------------------------------------
        Route::post('l3/checkout', [
            'as' => 'as.embed.checkout',
            'uses' => 'App\Appointment\Controllers\Embed\Layout3@checkout'
        ]);

        Route::any('l3/checkout/confirm', [
            'as'   => 'as.embed.checkout.confirm',
            'uses' => 'App\Appointment\Controllers\Embed\Layout3@confirm'
        ]);

        Route::post('l3/payment', [
            'as' => 'as.embed.l3.payment',
            'uses' => 'App\Appointment\Controllers\Embed\Layout3@payment'
        ]);

        Route::get('{hash}', [
            'as' => 'as.embed.embed',
            'uses' => 'App\Appointment\Controllers\Embed\Base@index'
        ]);
    });
});
