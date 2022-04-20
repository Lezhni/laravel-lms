<?php

namespace Modules\Pages\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Pages\Models\Category;
use Modules\Pages\Models\Page;
use Modules\Pages\Observers\CategoryObserver;
use Modules\Pages\Observers\PageObserver;

/**
 *
 */
class PagesServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(NovaServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * @return void
     */
    public function boot()
    {
        Category::observe(CategoryObserver::class);
        Page::observe(PageObserver::class);
    }
}
