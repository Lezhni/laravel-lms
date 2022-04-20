<?php

namespace Modules\Api\Providers;

use App\Providers\AuthServiceProvider as AppAuthServiceProvider;

/**
 * Class AuthServiceProvider
 * @package Modules\Api\Providers
 */
class AuthServiceProvider extends AppAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Api\Models\AccessToken::class => \Modules\Api\Policies\AccessTokenPolicy::class,
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