<?php
/*
|--------------------------------------------------------------------------
| Module Flash Deals routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'flash-deal',
], function () {

    Route::group([
        'before' => ['auth']
    ], function () {

        // Services
        \App\FlashDeal\Controllers\Services::crudRoutes(
            'services',
            'fd.services'
        );

        // Coupons
        \App\FlashDeal\Controllers\Coupons::crudRoutes(
            'coupons',
            'fd.coupons'
        );

        // Flash deals
        \App\FlashDeal\Controllers\FlashDeals::crudRoutes(
            'flash-deals',
            'fd.flash_deals'
        );

        \App\FlashDeal\Controllers\FlashDealDates::crudRoutes(
            'flash-deal-dates',
            'fd.flash_deal_dates'
        );

        Route::get('/{tab?}', [
            'as' => 'fd.index',
            'uses' => 'App\FlashDeal\Controllers\Index@index'
        ]);

    });

    Route::get('view/{id}', [
        'as' => 'fd.view',
        'uses' =>  'App\FlashDeal\Controllers\FlashDeals@view'
    ]);

    Route::post('cart', [
        'as'   => 'fd.cart',
        'uses' =>  'App\FlashDeal\Controllers\FlashDeals@cart'
    ]);
});
