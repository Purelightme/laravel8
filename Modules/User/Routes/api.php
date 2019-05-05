<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('user')->group(function() {
    Route::resource('users', 'UserController');

});

Route::prefix('user')->group(function (){
    Route::get('retrieve_register_code','UserController@retrieve_register_code');
});