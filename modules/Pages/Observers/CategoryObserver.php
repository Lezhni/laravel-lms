<?php

namespace Modules\Pages\Observers;

use Illuminate\Support\Str;
use Modules\Pages\Models\Category;

/**
 *
 */
class CategoryObserver
{
    /**
     * @param \Modules\Pages\Models\Category $category
     */
    public function saving(Category $category)
    {
        if (is_string($category->alias)) {
            return;
        }

        $category->alias = Str::slug($category->name);
    }
}