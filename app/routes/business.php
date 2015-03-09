<?php
/*
|--------------------------------------------------------------------------
| Single business
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'businesses'], function() {

    Route::get('/', [
        'as'    => 'businesses',
        'uses'  => 'App\Core\Controllers\Front@businesses'
    ]);

    Route::get('category/{id}-{slug}', [
        'as'    => 'business.category',
        'uses'  => 'App\Core\Controllers\Front@category'
    ]);

    Route::get('{id}-{slug?}', [
        'as'    => 'business.index',
        'uses'  => 'App\Core\Controllers\Ajax\Search@showBusiness'
    ]);
});
