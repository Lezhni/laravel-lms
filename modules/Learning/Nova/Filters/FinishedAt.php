<?php

namespace Modules\Learning\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;

class FinishedAt extends DateFilter
{
    /**
     * @var string
     */
    public $name = 'Дата завершения';

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
        $value = $value->setTime(23, 59, 59);

        return $query->where('finished_at', '<=', $value);
    }
}
