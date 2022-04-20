<?php

namespace Modules\Learning\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Notifications\Console\DeleteExpiredNotifications;

class ScheduleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command(DeleteExpiredNotifications::class)->daily();
        });
    }
}