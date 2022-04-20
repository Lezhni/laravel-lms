<?php

namespace Modules\Pages\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Pages\Models\Category;
use Modules\Pages\Models\Page;

/**
 *
 */
class PagesRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategories(): Collection
    {
        return Category::published()
            ->with('pages')
            ->get();
    }

    /**
     * @param string $alias
     * @return \Modules\Pages\Models\Page|null
     */
    public function getPage(string $alias): ?Page
    {
        return Page::published()
            ->where('alias', $alias)
            ->first();
    }
}