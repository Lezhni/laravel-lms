<?php

namespace Modules\Pages\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::middleware('web')
            ->group(module_path('Pages', 'Routes/web.php'));

        Route::prefix('api')
            ->middleware('api')
            ->group(module_path('Pages', 'Routes/api.php'));
    }
}