<?php

namespace Modules\Pages\Observers;

use Illuminate\Support\Str;
use Modules\Pages\Models\Page;

/**
 *
 */
class PageObserver
{
    /**
     * @param \Modules\Pages\Models\Page $page
     */
    public function saving(Page $page)
    {
        if (is_string($page->alias)) {
            return;
        }

        $page->alias = Str::slug($page->name);
    }
}