<?php

Route::post('login', \App\Http\Controllers\Auth\LoginController::class . '@login');
Route::post('logout', \App\Http\Controllers\Auth\LoginController::class . '@logout');

Route::group(['prefix' => 'password'], function () {
    Route::post('send-link', \App\Http\Controllers\Auth\PasswordResetController::class . '@sendLink');
    Route::post('reset', \App\Http\Controllers\Auth\PasswordResetController::class . '@reset');
});

Route::group(['prefix' => 'countries'], function () {
    Route::get('', \App\Http\Controllers\CountriesController::class . '@getCountries');
    Route::get('{country}', \App\Http\Controllers\CountriesController::class . '@getCountryCities');
});

Route::group(['middleware' => ['auth:api', 'user.lastactivity']], function () {
    Route::get('profile', \App\Http\Controllers\ProfileController::class . '@getProfile');
    Route::post('profile', \App\Http\Controllers\ProfileController::class . '@updateProfile');
});