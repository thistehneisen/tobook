<?php
/*
|--------------------------------------------------------------------------
| Front pages routes
|--------------------------------------------------------------------------
*/
Route::group([], function () {
    Route::get('/', [
        'as'    => 'home',
        'uses'  => 'App\Core\Controllers\Front@home'
    ]);

    Route::get('about', [
        'as' => 'front.about',
        'uses' => 'App\Core\Controllers\Front@about',
    ]);

    Route::get('contact', [
        'as' => 'front.contact',
        'uses' => 'App\Core\Controllers\Front@contact',
    ]);

    Route::get('partners', [
        'as' => 'front.partners',
        'uses' => 'App\Core\Controllers\Front@partners',
    ]);

    Route::get('resellers', [
        'as' => 'front.resellers',
        'uses' => 'App\Core\Controllers\Front@resellers',
    ]);

    Route::get('media-companies', [
        'as' => 'front.media',
        'uses' => 'App\Core\Controllers\Front@media',
    ]);

    Route::get('directories', [
        'as' => 'front.directories',
        'uses' => 'App\Core\Controllers\Front@directories',
    ]);

    Route::group(['prefix' => 'business'], function () {
        Route::get('/', [
            'as'    => 'intro-business',
            'uses'  => 'App\Core\Controllers\Front@businessIndex'
        ]);

        Route::get('website-list', [
            'as' => 'intro-website-list',
            'uses' => 'App\Core\Controllers\Front@businessWebsiteList',
        ]);

        Route::get('loyalty', [
            'as' => 'intro-loyalty',
            'uses' => 'App\Core\Controllers\Front@businessLoyalty',
        ]);

        Route::get('online-booking', [
            'as'    => 'intro-online-booking',
            'uses'  => 'App\Core\Controllers\Front@businessOnlineBooking',
        ]);

        Route::get('customer-registration', [
            'as' => 'intro-customer-registration',
            'uses' => 'App\Core\Controllers\Front@businessMarketingTools',
        ]);

        Route::get('cashier', [
            'as' => 'intro-cashier',
            'uses' => 'App\Core\Controllers\Front@businessCashier',
        ]);

        Route::get('marketing-tools', [
            'as' => 'intro-marketing-tools',
            'uses' => 'App\Core\Controllers\Front@businessMarketingTools',
        ]);
    });
});

// JS localization
Route::get('jslocale.json', [
    'as'    => 'ajax.jslocale',
    'uses'  => 'App\Core\Controllers\Ajax\JsLocale@getJsLocale'
]);
