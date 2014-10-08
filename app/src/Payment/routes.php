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

});
