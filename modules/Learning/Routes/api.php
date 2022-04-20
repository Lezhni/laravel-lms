<?php

Route::group(['middleware' => ['auth:api', 'user.lastactivity']], function () {
    Route::get('dashboard', \Modules\Learning\Http\Controllers\DashboardController::class . '@getStudentDashboard');

    Route::group(['prefix' => 'course/{course}'], function () {
        Route::get('', \Modules\Learning\Http\Controllers\CourseController::class . '@getCourse');

        Route::group(['prefix' => 'lesson/{lesson}'], function () {
            Route::get('', \Modules\Learning\Http\Controllers\LessonController::class . '@getLesson');

            Route::group(['prefix' => 'quiz/{quiz}'], function () {
                Route::get('', \Modules\Learning\Http\Controllers\QuizController::class . '@getQuiz');
                Route::get('results', \Modules\Learning\Http\Controllers\QuizController::class . '@getQuizResults');
                Route::post('process', \Modules\Learning\Http\Controllers\QuizController::class . '@processQuizResults');
            });

            Route::group(['prefix' => 'homework'], function () {
                Route::get('', \Modules\Learning\Http\Controllers\HomeworkController::class . '@getHomework');
                Route::post('message', \Modules\Learning\Http\Controllers\HomeworkController::class . '@sendChatMessage');
            });
        });
    });
});