<?php

namespace Modules\Learning\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

/**
 *
 */
class HomeworkGradeStatus extends Filter
{
    /**
     * @var string
     */
    public $component = 'select-filter';

    /**
     * @var string
     */
    public $name = 'Наличие оценки у ДЗ';

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
        return ($value === 'without')
            ? $query->whereNull('grade')
            : $query->whereNotNull('grade');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request): array
    {
        return [
            'ДЗ без оценки' => 'without',
            'ДЗ с оценкой' => 'with',
        ];
    }
}
