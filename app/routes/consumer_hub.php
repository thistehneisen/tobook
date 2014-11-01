<?php
/*
|--------------------------------------------------------------------------
| Consumer hub routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'consumer-hub',
    'before' => ['auth', 'only.business', 'premium.modules:consumers']
], function () {
    \App\Consumers\Controllers\Hub::crudRoutes(
        '/',
        'consumer-hub'
    );

    Route::get('history', [
        'as' => 'consumer-hub.history',
        'uses' => 'App\Consumers\Controllers\Hub@getHistory',
    ]);

    Route::get('/{tab?}', [
        'as' => 'consumer-hub',
        'uses' => 'App\Consumers\Controllers\Hub@upsertHandler',
    ]);
});
