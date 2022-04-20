<?php

namespace Modules\Pages\Providers;

use App\Providers\AuthServiceProvider as AppAuthServiceProvider;

/**
 *
 */
class AuthServiceProvider extends AppAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Pages\Models\Category::class => \Modules\Pages\Policies\CategoryPolicy::class,
        \Modules\Pages\Models\Page::class => \Modules\Pages\Policies\PagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}