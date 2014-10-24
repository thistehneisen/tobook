<?php
/*
|--------------------------------------------------------------------------
| Module Images routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'images',
    'before' => ['auth']
], function () {

    Route::post('upload', [
        'as' => 'images.upload',
        'uses' => 'App\Core\Controllers\Images@upload'
    ]);

    Route::get('delete/{id}', [
        'as' => 'images.delete',
        'uses' => 'App\Core\Controllers\Images@delete'
    ]);

});
