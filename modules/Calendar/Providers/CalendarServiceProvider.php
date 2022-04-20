<?php

namespace Modules\Calendar\Providers;

use Illuminate\Support\ServiceProvider;

class CalendarServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}