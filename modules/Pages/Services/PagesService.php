<?php

namespace Modules\Pages\Services;

use App\Helpers\BlockEditor\Helper;
use Modules\Pages\Models\Page;

/**
 *
 */
class PagesService
{
    /**
     * @param \App\Helpers\BlockEditor\Helper $contentHelper
     */
    public function __construct(protected Helper $contentHelper)
    {
    }

    /**
     * @param \Modules\Pages\Models\Page $page
     * @return \Modules\Pages\Models\Page
     */
    public function formatPage(Page $page): Page
    {
        $content = $page->content;
        $page->content = $this->contentHelper->prepareContent($content);

        return $page;
    }
}