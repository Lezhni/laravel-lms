<?php

namespace Modules\Learning\Nova\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Nova\Filters\Filter;

class LessonTeacher extends Filter
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
    public $name = 'Преподаватель';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value): Builder
    {
        return $query
            ->where('teacher_id', $value)
            ->orWhereHas('course.teachers', function (Builder $q) use ($value) {
                $q->where('teacher_id', $value);
            });
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function options(Request $request): Collection
    {
        return User::admins()->get()->pluck('id', 'name');
    }
}
