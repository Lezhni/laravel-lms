<?php

namespace Modules\Learning\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Modules\Learning\Models\Lesson\Lesson as LessonModel;

/**
 *
 */
class Lesson extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * @var string
     */
    public $name = 'Занятие';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('lesson_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request): array
    {
        $options = [];

        $lessons = LessonModel::with('course')->get();
        foreach ($lessons as $lesson) {
            $options[] = ['name' => $lesson->name, 'value' => $lesson->id, 'group' => $lesson->course->name];
        }

        return $options;
    }
}
