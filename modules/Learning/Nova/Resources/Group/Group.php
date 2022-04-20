<?php

namespace Modules\Learning\Nova\Resources\Group;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Modules\Learning\Nova\Flexible\Presets\CourseContent;
use Modules\Learning\Nova\Resources\Course;
use Modules\Learning\Nova\Resources\Student;
use Titasgailius\SearchRelations\SearchesRelations;
use Whitecube\NovaFlexibleContent\Flexible;

/**
 * Class Group
 * @package Modules\Learning\Nova\Resources\Group
 */
class Group extends Resource
{
    use SearchesRelations;

    /**
     * @var string
     */
    public static $group = 'Обучение';

    /**
     * @var int
     */
    public static int $priority = 1;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \Modules\Learning\Models\Group\Group::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static array $searchRelations = [
        'course' => ['name'],
    ];

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

            Text::make('Название', 'name')
                ->rules(['required', 'string'])
                ->sortable(),

            BelongsTo::make('Курс', 'course', Course::class)
                ->rules(['required', 'exists:courses,id'])
                ->sortable(),

            DateTime::make('Дата старта', 'started_at')
                ->rules(['required', 'date'])
                ->sortable(),

            DateTime::make('Дата завершения', 'finished_at')
                ->rules(['required', 'date', 'after:started_at'])
                ->sortable(),

            DateTime::make('Дата закрытия доступа', 'access_closed_at')
                ->rules(['nullable', 'date', 'after:finished_at'])
                ->nullable()
                ->sortable()
                ->help('Можно указать, если доступ к курсу останется ещё какое-то время после завершения'),

            Flexible::make('Доп. описание курса', 'course_content')
                ->preset(CourseContent::class)
                ->help('Добавляет в описание курса дополнительную информацию. Только для этого потока'),

            HasMany::make('Переопределение занятий', 'overriddenLessons', GroupLesson::class),

            HasMany::make('Студенты', 'students', Student::class),
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Потоки';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Поток';
    }
}
