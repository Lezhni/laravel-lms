<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

/**
 * Class PublicationStatus
 * @package App\Nova\Filters
 */
class PublicationStatus extends Filter
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
    public $name = 'Статус публикации';

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
        return $query->where('published', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request): array
    {
        return [
            'Опубликованные' => 1,
            'Неопубликованные' => 0,
        ];
    }
}
