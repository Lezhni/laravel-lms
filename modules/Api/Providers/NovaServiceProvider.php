<?php

namespace Modules\Api\Providers;

use App\Providers\NovaServiceProvider as BaseNovaServiceProvider;
use Laravel\Nova\Nova;
use Modules\Api\Nova\Resources\AccessToken;

/**
 * Class NovaServiceProvider
 * @package Modules\Learning\Providers
 */
class NovaServiceProvider extends BaseNovaServiceProvider
{
    /**
     * @return void
     */
    public function resources()
    {
        Nova::resources([
            \Modules\Api\Nova\Resources\AccessToken::class,
        ]);
    }
}
