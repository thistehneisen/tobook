<?php
/*
|--------------------------------------------------------------------------
| Single business
|--------------------------------------------------------------------------
*/
Route::get('business/{id}-{slug?}', [
    'as'    => 'business.index',
    'uses'  => 'App\Core\Controllers\Ajax\Search@showBusiness'
]);
