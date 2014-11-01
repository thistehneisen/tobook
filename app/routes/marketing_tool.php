<?php
/*
|--------------------------------------------------------------------------
| Marketing Tool routes
|--------------------------------------------------------------------------
*/
Route::group([
    'before' => ['auth', 'only.business', 'premium.modules:marketing'],
    'prefix' => 'marketing-tools'
], function () {
    Route::resource('campaigns', 'App\MarketingTool\Controllers\Campaign', [
        'names' => [
            'index'     => 'mt.campaigns.index',
            'create'    => 'mt.campaigns.create',
            'edit'      => 'mt.campaigns.edit',
            'store'     => 'mt.campaigns.store',
            'update'    => 'mt.campaigns.update',
            'destroy'   => 'mt.campaigns.delete',
        ]
    ]);
    Route::post('campaigns/statistics', [
        'as'   => 'mt.campaigns.statistics',
        'uses' => 'App\MarketingTool\Controllers\Campaign@statistics'
    ]);
    Route::post('campaigns/duplication', [
        'as'   => 'mt.campaigns.duplication',
        'uses' => 'App\MarketingTool\Controllers\Campaign@duplication'
    ]);
    Route::post('campaigns/sendIndividual', [
        'as'   => 'mt.campaigns.sendIndividual',
        'uses' => 'App\MarketingTool\Controllers\Campaign@sendIndividual'
    ]);
    Route::post('campaigns/sendGroup', [
        'as'   => 'mt.campaigns.group',
        'uses' => 'App\MarketingTool\Controllers\Campaign@sendGroup'
    ]);

    Route::resource('sms', 'App\MarketingTool\Controllers\Sms', [
        'names' => [
            'index'     => 'mt.sms.index',
            'create'    => 'mt.sms.create',
            'edit'      => 'mt.sms.edit',
            'store'     => 'mt.sms.store',
            'update'    => 'mt.sms.update',
            'destroy'   => 'mt.sms.delete',
        ]
    ]);
    Route::post('sms/sendIndividual', [
        'as'   => 'mt.sms.sendIndividual',
        'uses' => 'App\MarketingTool\Controllers\Sms@sendIndividual'
    ]);
    Route::post('sms/sendGroup', [
        'as'   => 'mt.sms.group',
        'uses' => 'App\MarketingTool\Controllers\Sms@sendGroup'
    ]);

    Route::resource('templates', 'App\MarketingTool\Controllers\Template', [
        'names' => [
            'index'     => 'mt.templates.index',
            'create'    => 'mt.templates.create',
            'edit'      => 'mt.templates.edit',
            'store'     => 'mt.templates.store',
            'update'    => 'mt.templates.update',
            'destroy'   => 'mt.templates.delete',
        ]
    ]);
    Route::post('templates/load', [
        'as'   => 'mt.templates.load',
        'uses' => 'App\MarketingTool\Controllers\Template@load'
    ]);

    Route::resource('settings', 'App\MarketingTool\Controllers\Setting', [
        'names' => [
            'index'     => 'mt.settings.index',
            'create'    => 'mt.settings.create',
            'edit'      => 'mt.settings.edit',
            'store'     => 'mt.settings.store',
            'update'    => 'mt.settings.update',
            'destroy'   => 'mt.settings.delete',
        ]
    ]);

    Route::resource('groups', 'App\MarketingTool\Controllers\Group', [
        'names' => [
            'index'     => 'mt.groups.index',
            'edit'      => 'mt.groups.edit',
            'store'     => 'mt.groups.store',
            'update'    => 'mt.groups.update',
            'destroy'   => 'mt.groups.delete',
        ]
    ]);
    Route::post('groups/create', [
        'as'   => 'mt.groups.create',
        'uses' => 'App\MarketingTool\Controllers\Group@create'
    ]);

    Route::resource('consumers', 'App\MarketingTool\Controllers\Consumer', [
        'names' => [
            'index'     => 'mt.consumers.index',
            'show'      => 'mt.consumers.show',
        ]
    ]);
});
