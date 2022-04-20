<?php

namespace Modules\Learning\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Filters\BooleanFilter;

/**
 *
 */
class OnlyCurrentTeacherCourses extends BooleanFilter
{
    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        if (!$value['teachers-courses']) {
            return $query;
        }

        return $query->whereHas('course.teachers', function (Builder $query) {
            $query->where('teacher_id', Auth::id());
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Показать только мои курсы' => 'teachers-courses',
        ];
    }

    /**
     * @return string|null
     */
    public function name(): ?string
    {
        return null;
    }
}
