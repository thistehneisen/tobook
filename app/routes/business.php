<?php
/*
|--------------------------------------------------------------------------
| Business category
|--------------------------------------------------------------------------
*/
Route::get('categories/{id}-{slug}', [
    'as'    => 'business.master_category',
    'uses'  => 'App\Core\Controllers\Front@masterCategory'
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

    Route::get('category/{id}-{slug}', [
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

    Route::get('{id}-{slug?}', [
        'as'    => 'business.index',
        'uses'  => 'App\Core\Controllers\Ajax\Search@showBusiness'
    ]);
});
