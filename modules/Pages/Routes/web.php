<?php

Route::get('pages/{alias}', \App\Http\Controllers\HomeController::class)->name('page');