<?php

namespace Modules\Learning\Nova\Resources\Lesson;

use App\Nova\Actions\PublishResource;
use App\Nova\Actions\UnpublishResource;
use App\Nova\Filters\PublicationStatus;
use App\Nova\Resource;
use App\Nova\Resources\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Modules\Learning\Nova\Filters\Course as CourseFilter;
use Modules\Learning\Nova\Filters\LessonTeacher;
use Modules\Learning\Nova\Flexible\Presets\LessonContent;
use Modules\Learning\Nova\Resources\Course;
use Modules\Learning\Nova\Resources\Lesson\Attachment\Attachment;
use Modules\Learning\Nova\Resources\Quiz\Quiz;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;
use Titasgailius\SearchRelations\SearchesRelations;
use Whitecube\NovaFlexibleContent\Flexible;

/**
 * Class Lesson
 * @package Modules\Learning\Nova\Resources
 */
class Lesson extends Resource
{
    use SearchesRelations, HasSortableRows;

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
    public static string $model = \Modules\Learning\Models\Lesson\Lesson::class;

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
     * @var int
     */
    public static $perPageViaRelationship = 999;

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

            Boolean::make('Завершено', 'finished')
                ->rules(['required', 'boolean'])
                ->default(false)
                ->sortable()
                ->help('Занятие автоматически завершится, если будет добавлена запись на видео, и дата старта занятия раньше текущей даты'),

            Text::make('Название', 'name')
                ->resolveUsing(function () {
                    $nameExcerpt = strlen($this->name) > 50 ? mb_substr($this->name, 0, 50) . '...' : $this->name;
                    return "<span title='{$this->name}'>{$nameExcerpt}</span>";
                })
                ->asHtml()
                ->onlyOnIndex()
                ->sortable(),

            Text::make('Название', 'name')
                ->rules(['required', 'string'])
                ->hideFromIndex(),

            Flexible::make('Контент', 'content')
                ->preset(LessonContent::class),

            BelongsTo::make('Курс', 'course', Course::class)
                ->rules(['required', 'exists:courses,id'])
                ->sortable(),

            BelongsTo::make('Преподаватель', 'teacher', User::class)
                ->rules(['nullable', 'exists:users,id'])
                ->nullable()
                ->sortable()
                ->hideFromIndex()
                ->help('Необязательное поле. Можно указать, если занятие проводит другой преподаватель'),

            DateTime::make('Дата и время начала', 'started_at')
                ->rules(['nullable', 'date'])
                ->nullable()
                ->sortable(),

            Panel::make('Запись занятия', [
                Text::make('Ссылка', 'record_link')
                    ->rules(['nullable', 'url', 'max:255'])
                    ->onlyOnForms()
                    ->help('Поддерживаемые видеохостинги: Vimeo, Youtube, видео с Google Drive'),

                Text::make('Ссылка на запись занятия', 'record_link')
                    ->resolveUsing(function () {
                        if (!$this->record_link) return null;
                        return "<a href='{$this->record_link}' class='no-underline dim text-primary' target='_blank'>{$this->record_link}</a>";
                    })
                    ->asHtml()
                    ->onlyOnDetail(),

                Text::make('Заголовок / комментарий', 'record_name')
                    ->rules(['nullable', 'string', 'max:255'])
            ]),

            HasMany::make('Материалы к занятию', 'attachments', Attachment::class),

            HasMany::make('Тесты', 'allQuizzes', Quiz::class),
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
            new CourseFilter,
            new LessonTeacher,
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
        return 'Занятия';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Занятие';
    }
}
