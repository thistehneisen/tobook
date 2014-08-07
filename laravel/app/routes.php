<?php

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

Route::get('/', [
    'as' => 'home',
    'uses' => 'App\Controllers\Home@index'
]);

Route::get('login', [
	'as' => 'auth.login',
	'uses' => 'App\Controllers\Auth@login'
]);

Route::post('login', [
	'uses' => 'App\Controllers\Auth@doLogin'
]);
