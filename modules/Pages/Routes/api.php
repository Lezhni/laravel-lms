<?php

Route::group(['middleware' => 'user.lastactivity', 'prefix' => 'pages'], function () {
    Route::get('', \Modules\Pages\Http\Controllers\PagesController::class . '@getCategories');
    Route::get('{alias}', \Modules\Pages\Http\Controllers\PagesController::class . '@getPage');
});