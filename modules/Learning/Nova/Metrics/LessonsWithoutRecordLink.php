<?php

namespace Modules\Learning\Nova\Metrics;

use Illuminate\Support\Carbon;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Modules\Learning\Models\Lesson\Lesson;

/**
 * Class LessonsWithoutRecord
 * @package Modules\Learning\Nova\Metrics
 */
class LessonsWithoutRecordLink extends Value
{
    /**
     * @var string
     */
    public $name = 'Прошедших занятий без записи';

    /**
     * @var string
     */
    public $helpText = 'Администраторам - не забывайте добавлять записи к прошедшим занятиям!';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request): mixed
    {
        $query = Lesson::published()
            ->whereNull('record_link')
            ->where('started_at', '<=', Carbon::yesterday()->endOfDay());

        return $this->count($request, $query)->allowZeroResult();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges(): array
    {
        return [
            30 => __('За месяц'),
            365 => __('За год'),
            'ALL' => __('За всё время'),
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey(): string
    {
        return 'lessons-without-record';
    }
}
