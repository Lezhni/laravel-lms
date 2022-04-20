<?php

use CreaceptLms\HomeworkChat\Http\Controllers\HomeworkChatController;

Route::get('messages', HomeworkChatController::class . '@getMessages');
Route::post('messages', HomeworkChatController::class . '@addMessage');