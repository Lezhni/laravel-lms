<?php

namespace Modules\Learning\Providers;

use App\Providers\NovaServiceProvider as BaseNovaServiceProvider;
use Laravel\Nova\Nova;

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
            \Modules\Learning\Nova\Resources\Student::class,
            \Modules\Learning\Nova\Resources\Course::class,
            \Modules\Learning\Nova\Resources\Group\Group::class,
            \Modules\Learning\Nova\Resources\Group\GroupLesson::class,
            \Modules\Learning\Nova\Resources\Attachment\Category::class,
            \Modules\Learning\Nova\Resources\Attachment\Attachment::class,
            \Modules\Learning\Nova\Resources\Lesson\Lesson::class,
            \Modules\Learning\Nova\Resources\Lesson\Attachment\Category::class,
            \Modules\Learning\Nova\Resources\Lesson\Attachment\Attachment::class,
            \Modules\Learning\Nova\Resources\Lesson\Homework\Homework::class,
            \Modules\Learning\Nova\Resources\Lesson\Homework\Grade::class,
            \Modules\Learning\Nova\Resources\Enrollment::class,
            \Modules\Learning\Nova\Resources\Quiz\Quiz::class,
            \Modules\Learning\Nova\Resources\Quiz\Question::class,
            \Modules\Learning\Nova\Resources\Quiz\Answer::class,
        ]);
    }
}
