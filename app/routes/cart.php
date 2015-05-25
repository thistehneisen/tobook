<?php
/*
|-------------------------------------------------------------------------------
| Cart routes
|-------------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'cart'
], function () {

    Route::get('/', [
        'as' => 'cart.index',
        'uses' => 'App\Cart\Controllers\Index@index'
    ]);

    Route::group(['before' => ['cart.existed']], function () {
        Route::get('checkout', [
            'as' => 'cart.checkout',
            'uses' => 'App\Cart\Controllers\Index@checkout'
        ]);

        Route::get('consumer', [
            'as' => 'cart.consumer',
            'uses' => 'App\Cart\Controllers\Consumers@index'
        ]);

        Route::post('consumer', [
            'uses' => 'App\Cart\Controllers\Consumers@submit'
        ]);

        Route::get('remove/{id}', [
            'as' => 'cart.remove',
            'uses' => 'App\Cart\Controllers\Index@remove'
        ]);

        Route::post('payment', [
            'as' => 'cart.payment',
            'uses' => 'App\Cart\Controllers\Index@payment'
        ]);
    });

});
