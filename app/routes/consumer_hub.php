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

    \App\Consumers\Controllers\EmailTemplate::crudRoutes(
        'email-templates',
        'consumer-hub.email_templates'
    );

    \App\Consumers\Controllers\Sms::crudRoutes(
        'sms',
        'consumer-hub.sms'
    );

    Route::group([
        'prefix' => 'history',
    ], function () {
        Route::get('/', [
            'as' => 'consumer-hub.history',
            'uses' => 'App\Consumers\Controllers\Hub@getHistory',
        ]);

        Route::get('email', [
            'as' => 'consumer-hub.history.email',
            'uses' => 'App\Consumers\Controllers\EmailTemplate@history',
        ]);

        Route::get('email', [
            'as' => 'consumer-hub.history.sms',
            'uses' => 'App\Consumers\Controllers\Sms@history',
        ]);
    });

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
