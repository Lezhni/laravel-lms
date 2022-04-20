<?php

namespace Modules\Notifications\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Notifications\Console\DeleteExpiredNotifications;
use Modules\Notifications\Helpers\NotificationsHelper;

class NotificationsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ScheduleServiceProvider::class);

        $this->app->singleton(NotificationsHelper::class);

        $this->commands([
            DeleteExpiredNotifications::class,
        ]);
    }

    /**
     * @return void
     */
    public function boot()
    {
    }
}
