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

//未登录
Route::prefix('user')->group(function (){

    Route::get('retrieve_register_code','UserController@retrieve_register_code');
    Route::post('users','UserController@store');

    Route::get('retrieve_login_code','UserController@retrieve_login_code');
    Route::post('login_by_code','UserController@login_by_code');
    Route::post('login_by_password','UserController@login_by_password');

    Route::get('retrieve_find_password_code','UserController@retrieve_find_password_code');
    Route::post('find_password_by_code','UserController@find_password_by_code');

});


//已登录
Route::middleware(['auth:api'])->group(function (){
    Route::prefix('user')->group(function (){
        Route::get('users','UserController@index');
        Route::post('change_password','UserController@change_password');
    });
});