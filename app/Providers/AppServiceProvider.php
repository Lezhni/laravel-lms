<?php

namespace App\Providers;

use App\Helpers\BlockEditor\Helper;
use App\Models\User;
use App\Observers\UserObserver;
use App\Repositories\StudentRepository;
use App\Services\CountriesService;
use App\Services\StudentService;
use App\Services\UploadsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(NovaServiceProvider::class);

        $this->app->singleton(Helper::class);

        $this->app->singleton(StudentService::class);
        $this->app->singleton(UploadsService::class);
        $this->app->singleton(CountriesService::class);

        $this->app->singleton(StudentRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
    }
}
