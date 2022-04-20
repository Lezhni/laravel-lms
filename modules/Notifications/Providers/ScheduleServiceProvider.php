<?php

namespace Modules\Notifications\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Modules\Learning\Console\Commands\NotifyAboutLessonStart;
use Modules\Learning\Console\Commands\RemoveExpiredLessonLink;

class ScheduleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->call(NotifyAboutLessonStart::class)->everyFiveMinutes();
            $schedule->call(RemoveExpiredLessonLink::class)->dailyAt('23:59');
        });
    }
}