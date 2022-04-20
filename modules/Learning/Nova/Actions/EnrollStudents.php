<?php

namespace Modules\Learning\Nova\Actions;

use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Enrollment;
use Throwable;

/**
 *
 */
class EnrollStudents extends Action
{
    /**
     * @var string
     */
    public $name = 'Зачислить на курс';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models): mixed
    {
        foreach ($models as $student) {
            try {
                $enrollment = Enrollment::create([
                    'course_id' => $fields->course_id,
                    'student_id' => $student->id,
                    'started_at' => $fields->started_at,
                    'finished_at' => $fields->finished_at,
                ]);
            } catch (Throwable $e) {
                continue;
            }
        }

        return Action::message('Студенты успешно зачислены. Проигнорированы студенты, зачисленные ранее');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Select::make('Курс', 'course_id')
                ->options(function () { return Course::get()->pluck('name', 'id');  })
                ->rules(['required', 'exists:courses,id']),

            Date::make('Дата зачисления', 'started_at')
                ->rules(['nullable', 'required_with:finished_at', 'date'])
                ->firstDayOfWeek(1),

            Date::make('Дата отчисления', 'finished_at')
                ->rules(['nullable', 'after:started_at', 'date'])
                ->firstDayOfWeek(1),
        ];
    }
}
