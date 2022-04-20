<?php

namespace Modules\Learning\Helpers;

use App\Helpers\BlockEditor\Helper;
use Modules\Learning\Models\Lesson\Homework\Homework;

/**
 * Class HomeworkHelper
 * @package Modules\Learning\Helpers
 */
class HomeworkHelper
{
    /**
     * HomeworkHelper constructor.
     * @param \App\Helpers\BlockEditor\Helper $contentHelper
     */
    public function __construct(protected Helper $contentHelper)
    {
    }

    /**
     * @param Homework $homework
     * @return Homework
     */
    public function prepareHomework(Homework $homework): Homework
    {
        $content = $homework->content;
        $homework->content = $this->contentHelper->prepareContent($content);

        return $homework;
    }
}