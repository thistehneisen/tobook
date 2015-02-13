<?php

Route::pattern('id', '[0-9]+');
Route::pattern('slug', '[a-z0-9-]+');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

require app_path().'/routes/search.php';
require app_path().'/routes/auth.php';
require app_path().'/routes/business.php';
require app_path().'/routes/user.php';
require app_path().'/routes/embed.php';
require app_path().'/routes/cart.php';
require app_path().'/routes/image.php';
require app_path().'/routes/admin.php';

//------------------------------------------------------------------------------
// Modules
//------------------------------------------------------------------------------
require app_path().'/routes/api.php';
require app_path().'/routes/consumer_hub.php';
require app_path().'/routes/appointment.php';
require app_path().'/routes/loyalty_card.php';
require app_path().'/routes/flash_deal.php';

//------------------------------------------------------------------------------
// Others
//------------------------------------------------------------------------------
// Home
Route::group([], function () {
    Route::get('/', [
        'as'    => 'home',
        'uses'  => 'App\Core\Controllers\Front@home'
    ]);

    Route::get('robots.txt', [
        'as'    => 'robots',
        'uses'  => 'App\Core\Controllers\Front@robots'
    ]);
});

// JS localization
Route::get('jslocale.json', [
    'as'    => 'ajax.jslocale',
    'uses'  => 'App\Core\Controllers\Ajax\JsLocale@getJsLocale'
]);
