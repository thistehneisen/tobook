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

    \App\Consumers\Controllers\Group::crudRoutes(
        'groups',
        'consumer-hub.groups'
    );

    Route::get('history', [
        'as' => 'consumer-hub.history',
        'uses' => 'App\Consumers\Controllers\Hub@getHistory',
    ]);

    Route::get('import', [
        'before' => ['auth.admin'],
        'as' => 'consumer-hub.import',
        'uses' => 'App\Consumers\Controllers\Hub@import',
    ]);

    Route::post('import', [
        'before' => ['auth.admin'],
        'as' => 'consumer-hub.doImport',
        'uses' => 'App\Consumers\Controllers\Hub@doImport',
    ]);

    Route::get('/{tab?}', [
        'as' => 'consumer-hub',
        'uses' => 'App\Consumers\Controllers\Hub@upsertHandler',
    ]);
});
