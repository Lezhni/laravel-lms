<?php

Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'users'], function () {
        Route::get('', \Modules\Api\Http\Controllers\UserApiController::class . '@list');
        Route::post('', \Modules\Api\Http\Controllers\UserApiController::class . '@create');
        Route::get('get-by-field', \Modules\Api\Http\Controllers\UserApiController::class . '@getByField');
        Route::get('{id}', \Modules\Api\Http\Controllers\UserApiController::class . '@get');
        Route::patch('{id}', \Modules\Api\Http\Controllers\UserApiController::class . '@update');
        Route::delete('{id}', \Modules\Api\Http\Controllers\UserApiController::class . '@delete');

        if (\Nwidart\Modules\Facades\Module::has('Learning')) {
            Route::get('{id}/courses', \Modules\Api\Http\Controllers\UserApiController::class . '@listCourses');
        }
    });

    if (\Nwidart\Modules\Facades\Module::has('Learning')) {
        Route::group(['prefix' => 'courses'], function () {
            Route::get('', \Modules\Api\Http\Controllers\CourseApiController::class . '@list');
            Route::get('{id}', \Modules\Api\Http\Controllers\CourseApiController::class . '@get');
            Route::get('{id}/students', \Modules\Api\Http\Controllers\CourseApiController::class . '@listStudents');

            Route::group(['prefix' => '{course}/lessons'], function () {
                Route::get('', \Modules\Api\Http\Controllers\LessonApiController::class . '@list');
                Route::get('{id}', \Modules\Api\Http\Controllers\LessonApiController::class . '@get');
            });
        });

        Route::group(['prefix' => 'enrollments'], function () {
            Route::get('', \Modules\Api\Http\Controllers\EnrollmentsApiController::class . '@list');
            Route::post('', \Modules\Api\Http\Controllers\EnrollmentsApiController::class . '@create');
            Route::get('{id}', \Modules\Api\Http\Controllers\EnrollmentsApiController::class . '@get');
            Route::patch('{id}', \Modules\Api\Http\Controllers\EnrollmentsApiController::class . '@update');
            Route::delete('{id}', \Modules\Api\Http\Controllers\EnrollmentsApiController::class . '@delete');
        });
    }

});