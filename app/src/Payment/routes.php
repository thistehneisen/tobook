<?php
/*
|-------------------------------------------------------------------------------
| Routes for payment
|-------------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'payment'
], function () {

    Route::get('/', [
        'as' => 'payment.index',
        'uses' => 'App\Payment\Controllers\Index@index'
    ]);

    Route::get('test', function () {
        $cart = Cart::make([], 63);

        return Payment::redirect($cart, 1);
    });

    Route::post('purchase', [
        'as' => 'payment.purchase',
        'uses' => 'App\Payment\Controllers\Index@purchase'
    ]);

    Route::post('notify/{gateway}', [
        'as' => 'payment.notify',
        'uses' => 'App\Payment\Controllers\Index@notify'
    ]);

});
