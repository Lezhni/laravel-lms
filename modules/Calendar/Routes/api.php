<?php

Route::group(['middleware' => ['auth:api', 'user.lastactivity']], function () {
    Route::get('course-calendar/{course}',\Modules\Calendar\Http\Controllers\CalendarController::class . '@getCourseEvents');
});