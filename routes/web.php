<?php

Route::get('{alias?}', \App\Http\Controllers\HomeController::class)
    ->where('alias', '^(?!admin|api|nova-api|nova-vendor|horizon).*$')
    ->name('home');