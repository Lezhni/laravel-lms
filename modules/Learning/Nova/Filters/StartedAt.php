<?php

namespace Modules\Learning\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;

class StartedAt extends DateFilter
{
    /**
     * @var string
     */
    public $name = 'Дата начала';

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
        $value = Carbon::parse($value);
        $value = $value->setTime(0, 0);

        return $query->where('started_at', '>=', $value);
    }
}
