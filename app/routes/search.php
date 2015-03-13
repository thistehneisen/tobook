<?php
/*
|--------------------------------------------------------------------------
| Search Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'search'], function () {
    Route::get('/', [
        'as'    => 'search',
        'uses'  => 'App\Core\Controllers\Search@index'
    ]);

    Route::post('location', [
        'as'   => 'search.location',
        'uses' => 'App\Core\Controllers\Search@updateLocation'
    ]);

    Route::get('services.json', [
        'as'    => 'ajax.services',
        'uses'  => 'App\Core\Controllers\Ajax\Search@getServices'
    ]);

    Route::get('locations.json', [
        'as'    => 'ajax.locations',
        'uses'  => 'App\Core\Controllers\Ajax\Search@getLocations'
    ]);

    Route::get('business/{id}', [
        'as'    => 'ajax.showBusiness',
        'uses'  => 'App\Core\Controllers\Ajax\Search@showBusiness'
    ]);
});
