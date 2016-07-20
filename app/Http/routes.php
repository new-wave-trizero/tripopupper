<?php

Route::get('/', 'PopupController@showPopupsList');
Route::get('/popup', 'PopupController@showPopupsList');
Route::post('popup', 'PopupController@storePopup');
Route::get('popup/{popup}', 'PopupController@editPoup');
Route::put('popup/{popup}', 'PopupController@updatePopup');
Route::delete('popup/{popup}', 'PopupController@destroyPopup');

Route::auth();

APIRoute::version('v1', function () {
    ApiRoute::get('popup/{popup}', 'App\Http\Controllers\Api\PopupConfigController@getConfig');
});
