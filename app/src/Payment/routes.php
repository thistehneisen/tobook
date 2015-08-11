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

    Route::post('purchase', [
        'as' => 'payment.purchase',
        'uses' => 'App\Payment\Controllers\Index@purchase'
    ]);

    Route::any('notify/{gateway}', [
        'as' => 'payment.notify',
        'uses' => 'App\Payment\Controllers\Index@notify'
    ]);

    Route::get('success/{id?}', [
        'as' => 'payment.success',
        'uses' => 'App\Payment\Controllers\Index@success'
    ]);

    Route::get('cancel/{id}', [
        'as' => 'payment.cancel',
        'uses' => 'App\Payment\Controllers\Index@cancel'
    ]);
});
