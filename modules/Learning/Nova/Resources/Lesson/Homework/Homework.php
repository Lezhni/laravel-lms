<?php

namespace Modules\Learning\Nova\Resources\Lesson\Homework;

use App\Nova\Actions\PublishResource;
use App\Nova\Actions\UnpublishResource;
use App\Nova\Filters\PublicationStatus;
use App\Nova\Flexible\Presets\BlockEditor;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Modules\Learning\Nova\Filters\Lesson as LessonFilter;
use Modules\Learning\Nova\Resources\Lesson\Lesson;
use Titasgailius\SearchRelations\SearchesRelations;
use Whitecube\NovaFlexibleContent\Flexible;

/**
 * Class Homework
 * @package Modules\Learning\Nova\Resources\Lesson\Homework
 */
class Homework extends Resource
{
    use SearchesRelations;

    /**
     * @var string
     */
    public static $group = 'Обучение';

    /**
     * @var int
     */
    public static int $priority = 3;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \Modules\Learning\Models\Lesson\Homework\Homework::class;

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
        'lesson' => ['name'],
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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')
                ->sortable(),

            Boolean::make('Опубликовано', 'published')
                ->rules(['required', 'boolean'])
                ->default(false)
                ->sortable(),

            BelongsTo::make('Занятие', 'lesson', Lesson::class)
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            Select::make('Занятие', 'lesson_id')
                ->options(function () {
                    $options = [];
                    $lessons = \Modules\Learning\Models\Lesson\Lesson::with('course')->get();
                    foreach ($lessons as $lesson) {
                        $options[$lesson->id] = ['label' => $lesson->name, 'group' => $lesson->course->name];
                    }
                    return $options;
                })
                ->rules(['required', 'exists:lessons,id', 'unique:lessons_homeworks,lesson_id,{{resourceId}}'])
                ->onlyOnForms()
                ->sortable(),

            Flexible::make('Контент', 'content')
                ->preset(BlockEditor::class),

            Number::make('Максимальная оценка за задание', 'max_grade')
                ->rules(['required', 'integer'])
                ->sortable()
                ->help('Количество баллов, которые можно получить при выполнении задания'),
        ];
    }

    /**
     * Get the filters available on the entity.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request): array
    {
        return [
            new PublicationStatus,
            new LessonFilter,
        ];
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
            new PublishResource,
            new UnpublishResource,
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Домашние задания';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Домашнее задание';
    }
}
