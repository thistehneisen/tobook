<?php
/*
|--------------------------------------------------------------------------
| Front page
|--------------------------------------------------------------------------
*/
Route::post('contact', [
    'as'    => 'contact',
    'uses'  => 'App\Core\Controllers\Contact@send'
]);

Route::get('terms', [
    'as'    => 'terms',
    'uses'  => 'App\Core\Controllers\Front@staticPage'
]);

Route::get('policy', [
    'as'    => 'policy',
    'uses'  => 'App\Core\Controllers\Front@staticPage'
]);

Route::get('/about', [
    'as'    => 'about',
    'uses'  => 'App\Core\Controllers\Front@about'
]);

Route::get('/business', [
    'as'    => 'business',
    'uses'  => 'App\Core\Controllers\Front@business'
]);

Route::get('/intro', [
    'as'    => 'intro',
    'uses'  => 'App\Core\Controllers\Front@intro'
]);

// Home
Route::get('/sitemap', [
    'as'    => 'sitemap',
    'uses'  => 'App\Core\Controllers\SitemapController@index'
]);

// Home
Route::get('/', [
    'as'    => 'home',
    'uses'  => 'App\Core\Controllers\Front@home'
]);
