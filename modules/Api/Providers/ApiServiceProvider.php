<?php

namespace Modules\Api\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ApiServiceProvider
 * @package Modules\Api\Providers
 */
class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(NovaServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    public function boot()
    {
        $this->registerViews();

        \Modules\Api\Models\AccessToken::observe(\Modules\Api\Observers\AccessTokenObserver::class);
    }

    protected function registerViews()
    {
        $viewPath = base_path('resources/views/modules/api');
        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([$sourcePath => $viewPath]);
        $this->loadViewsFrom($sourcePath, 'api');
    }
}