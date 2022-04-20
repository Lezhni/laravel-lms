<?php

namespace Modules\Learning\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Modules\Learning\Models\Quiz\Question;

/**
 * Class AssignedToQuestion
 * @package Modules\Learning\Nova\Filters
 */
class QuizQuestion extends Filter
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
    public $name = 'Вопрос';

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
        return $query->where('question_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function options(Request $request): \Illuminate\Support\Collection
    {
        return Question::all()->pluck('id', 'name');
    }
}
