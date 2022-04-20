<?php

namespace Modules\Learning\Nova\Resources\Group;

use App\Nova\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Lesson\Lesson as LessonModel;
use Modules\Learning\Nova\Resources\Lesson\Lesson;

/**
 * Class GroupLesson
 * @package Modules\Learning\Nova\Resources\Group
 */
class GroupLesson extends Resource
{
    /**
     * @var string
     */
    public static $group = 'Обучение';

    /**
     * @var int
     */
    public static int $priority = 1;

    /**
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \Modules\Learning\Models\Group\GroupLesson::class;

    /**
     * @var string
     */
    public static string $sortColumn = 'id';

    /**
     * @var string
     */
    public static string $sortDirection = 'asc';

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')
                ->sortable(),

            BelongsTo::make('Группа', 'group', Group::class)
                ->rules(['required', 'exists:groups,id'])
                ->sortable(),

            BelongsTo::make('Занятие', 'lesson', Lesson::class)
                ->exceptOnForms()
                ->sortable(),

            Select::make('Занятие', 'lesson_id')
                ->readonly(function () {
                    return $this->resource->exists;
                })
                ->options(function () use ($request) {
                    $course = Course::whereHas('groups', function (Builder $query) use ($request) {
                        $query->where('groups.id', $request->viaResourceId);
                    })->first();
                    return LessonModel::select(['id', 'name', 'course_id'])
                        ->where('course_id', $course?->id)
                        ->get()
                        ->pluck('name', 'id');
                })
                ->rules(['required', 'exists:lessons,id'])
                ->onlyOnForms(),

            DateTime::make('Дата и время начала', 'started_at')
                ->rules(['required', 'date'])
                ->sortable(),

            Text::make('Ссылка на занятие', 'lesson_link')
                ->rules(['required', 'url', 'max:255'])
                ->hideFromIndex(),

            Text::make('Ссылка на запись занятия', 'record_link')
                ->rules(['nullable', 'url', 'max:255'])
                ->hideFromIndex(),
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
        $groupId = $request->post('group');

        $unique = Rule::unique('groups_lessons', 'lesson_id')->where('group_id', $groupId);
        if ($request->route('resourceId')) {
            $unique->ignore($request->route('resourceId'));
        }

        $uniqueValidator = Validator::make($request->only('lesson_id'), [
            'lesson_id' => [$unique],
        ]);

        if ($uniqueValidator->fails()) {
            $validator->errors()->add('lesson_id', 'Это занятие уже переопределено для потока');
        }
    }

    /**
     * Return the location to redirect the user after creation.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Laravel\Nova\Resource $resource
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource): string
    {
        return '/resources/' . Group::uriKey() . '/' . $resource->group_id;
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Laravel\Nova\Resource $resource
     * @return string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource): string
    {
        return '/resources/' . Group::uriKey() . '/' . $resource->group_id;
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Занятия потока';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Занятие потока';
    }
}
