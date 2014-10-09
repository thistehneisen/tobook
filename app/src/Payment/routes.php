<?php
/*
|-------------------------------------------------------------------------------
| Routes for payment
|-------------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'payment'
], function() {

    Route::get('/', [
        'as' => 'payment.index',
        'uses' => 'App\Payment\Controllers\Index@index'
    ]);

    Route::get('test', function () {
        $cart = Cart::make([], 63);
        return Payment::redirect($cart, 999);
    });

    Route::post('process', [
        'as' => 'payment.process',
        'uses' => 'App\Payment\Controllers\Index@process'
    ]);

    Route::post('notify/{gateway}', [
        'as' => 'payment.notify',
        'uses' => 'App\Payment\Controllers\Index@notify'
    ]);

});
