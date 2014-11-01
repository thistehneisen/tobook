<?php
/*
|--------------------------------------------------------------------------
| Loyalty Card routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'loyalty-card',
    'before' => ['auth', 'only.business', 'premium.modules:loyalty']
], function () {

    \App\LoyaltyCard\Controllers\Consumer::crudRoutes(
        'consumers',
        'lc.consumers'
    );

    \App\LoyaltyCard\Controllers\Offer::crudRoutes(
        'offers',
        'lc.offers'
    );

    \App\LoyaltyCard\Controllers\Voucher::crudRoutes(
        'vouchers',
        'lc.vouchers'
    );
});
