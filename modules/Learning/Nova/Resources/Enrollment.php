<?php

namespace Modules\Learning\Nova\Resources;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Learning\Helpers\EnrollmentStatuses;
use Modules\Learning\Models\Course as CourseModel;
use Modules\Learning\Models\Enrollment as EnrollmentModel;
use Modules\Learning\Nova\Actions\SuspendEnrollment;
use Modules\Learning\Nova\Actions\UnsuspendEnrollment;
use Modules\Learning\Nova\Resources\Group\Group;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use Titasgailius\SearchRelations\SearchesRelations;

/**
 * Class Enrollment
 * @package Modules\Learning\Nova\Resources
 */
class Enrollment extends Resource
{
    use SearchesRelations;

    /**
     * @var string
     */
    public static $group = 'Обучение - прогресс';

    /**
     * @var int
     */
    public static int $priority = 3;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = EnrollmentModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static array $searchRelations = [
        'student' => ['name', 'email'],
        'course' => ['name'],
        'group' => ['name'],
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        return [
            BelongsTo::make('Студент', 'student', Student::class)
                ->readonly(function () {
                    return $this->resource->exists;
                })
                ->rules(['required', 'exists:users,id'])
                ->searchable()
                ->sortable(),

            NovaBelongsToDepend::make('Курс', 'course', Course::class)
                ->options(CourseModel::all())
                ->rules(['required', 'exists:courses,id'])
                ->sortable()
                ->placeholder('Выберите курс'),

            NovaBelongsToDepend::make('Группа/поток', 'group', Group::class)
                ->optionsResolve(function (CourseModel $course) {
                    return $course->groups()->get(['id', 'name']);
                })
                ->rules(['nullable', 'exists:groups,id'])
                ->dependsOn('course')
                ->sortable()
                ->nullable()
                ->placeholder('Выберите группу/поток курса'),

            Date::make('Дата зачисления', 'started_at')
                ->resolveUsing(function () {
                    return ($this->resource->group_id == null)
                        ? $this->started_at?->format('Y-m-d')
                        : null;
                })
                ->rules(['nullable', 'required_with:finished_at', 'date'])
                ->firstDayOfWeek(1)
                ->sortable(),

            Date::make('Дата отчисления', 'finished_at')
                ->resolveUsing(function () {
                    return ($this->resource->group_id == null)
                        ? $this->finished_at?->format('Y-m-d')
                        : null;
                })
                ->rules(['nullable', 'after:started_at', 'date'])
                ->firstDayOfWeek(1)
                ->sortable(),

            Boolean::make('Приостановить обучение', 'suspended')
                ->rules(['required', 'boolean'])
                ->hideFromIndex()
                ->help('Если необходимо временно убрать у студента доступ к курсу'),

            Badge::make('Статус обучения', 'status')
                ->map([
                    EnrollmentStatuses::ACTIVE => 'success',
                    EnrollmentStatuses::INACTIVE => 'danger',
                    EnrollmentStatuses::SUSPENDED => 'warning',
                    EnrollmentStatuses::NOT_STARTED => 'info',
                ])
                ->sortable(),
        ];
    }

    /**
     * Handle any post-validation processing.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    protected static function afterValidation(NovaRequest $request, $validator)
    {
        $courseId = $request->post('course');

        $unique = Rule::unique('courses_enrollments', 'student_id')->where('course_id', $courseId);
        if ($request->route('resourceId')) {
            $unique->ignore($request->route('resourceId'));
        }

        $uniqueValidator = Validator::make($request->only('student'), [
            'student' => [$unique],
        ]);

        if ($uniqueValidator->fails()) {
            $validator->errors()->add('course', 'Этот студент уже зачислен на указанный курс');
        }
    }

    /**
     * Get the actions available on the entity.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request): array
    {
        return [
            new SuspendEnrollment,
            new UnsuspendEnrollment,
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Зачисления на курсы';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Зачисление на курс';
    }
}
