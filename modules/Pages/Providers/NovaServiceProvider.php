<?php

namespace Modules\Pages\Providers;

use App\Providers\NovaServiceProvider as BaseNovaServiceProvider;
use Laravel\Nova\Nova;

class NovaServiceProvider extends BaseNovaServiceProvider
{
    /**
     * @return void
     */
    public function resources()
    {
        Nova::resources([
            \Modules\Pages\Nova\Resources\Category::class,
            \Modules\Pages\Nova\Resources\Page::class,
        ]);
    }
}
