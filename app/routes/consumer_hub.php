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

    \App\Consumers\Controllers\SmsTemplate::crudRoutes(
        'sms-templates',
        'consumer-hub.sms_templates'
    );

    Route::get('bulk', ['as' => 'consumer-hub.bulk', function () {}]);
    Route::get('groups/bulk', function () {});

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

        Route::get('sms', [
            'as' => 'consumer-hub.history.sms',
            'uses' => 'App\Consumers\Controllers\SmsTemplate@history',
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
});
