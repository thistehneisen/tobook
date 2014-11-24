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

    \App\Consumers\Controllers\Campaign::crudRoutes(
        'campaigns',
        'consumer-hub.campaigns'
    );
    Route::get('campaigns/history', [
        'as' => 'consumer-hub.campaigns.history',
        'uses' => 'App\Consumers\Controllers\Campaign@history',
    ]);

    \App\Consumers\Controllers\Sms::crudRoutes(
        'sms',
        'consumer-hub.sms'
    );
    Route::get('sms/history', [
        'as' => 'consumer-hub.sms.history',
        'uses' => 'App\Consumers\Controllers\Sms@history',
    ]);

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
