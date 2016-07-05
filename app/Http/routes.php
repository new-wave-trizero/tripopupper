<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('popup', 'PopupController@showPopupsList');
Route::get('popup/suggest', 'PopupController@suggestPopupName');
Route::get('popup/{popup}', 'PopupController@showPoupDetail');
Route::post('popup', 'PopupController@storePopup');
Route::put('popup/{popup}', 'PopupController@updatePopup');
Route::delete('popup/{popup}', 'PopupController@deleteePoup');
