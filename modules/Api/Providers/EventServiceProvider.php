<?php

namespace Modules\Api\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Api\Events\UserCreated;
use Modules\Api\Listeners\SendPasswordToUser;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserCreated::class => [
            SendPasswordToUser::class,
        ],
    ];
}