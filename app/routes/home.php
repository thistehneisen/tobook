<?php
/*
|--------------------------------------------------------------------------
| Front page
|--------------------------------------------------------------------------
*/

Route::get('/about', [
    'as'    => 'home',
    'uses'  => 'App\Core\Controllers\Front@about'
]);

Route::get('/business', [
    'as'    => 'home',
    'uses'  => 'App\Core\Controllers\Front@business'
]);

Route::get('/intro', [
    'as'    => 'home',
    'uses'  => 'App\Core\Controllers\Front@intro'
]);

// Home
Route::get('/', [
    'as'    => 'home',
    'uses'  => 'App\Core\Controllers\Front@home'
]);
