<?php

use Modules\Notifications\Http\Controllers\NotificationsController;

Route::group(['middleware' => ['auth:api', 'user.lastactivity'], 'prefix' => 'notifications'], function () {
    Route::get('', NotificationsController::class . '@getNotifications');
    Route::put('{id?}', NotificationsController::class . '@readNotifications');
});