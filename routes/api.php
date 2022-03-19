<?php

use Illuminate\Support\Facades\Route;

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

# Auth
Route::post('register', 'AuthController@register')->name('auth.register');
Route::post('login', 'AuthController@login')->name('auth.login');

# Room
Route::group(['prefix' => 'rooms', 'as' => 'rooms.'], function() {
    Route::get('/', 'RoomController@index')->name('index');
});

Route::middleware('auth:sanctum')->group(function() {
    # Account
    Route::get('account/current-user', 'AccountController@currentUser')->name('account.current-user');

    # Owner
    Route::group(['prefix' => 'owner', 'as' => 'owner.', 'middleware' => 'owner'], function() {
        # Room
        Route::apiResource('rooms', 'Owner\RoomController');
    });
});
