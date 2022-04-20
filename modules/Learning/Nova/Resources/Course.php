<?php

namespace Modules\Learning\Nova\Resources;

use App\Models\User as UserModel;
use App\Nova\Actions\PublishResource;
use App\Nova\Actions\UnpublishResource;
use App\Nova\Filters\PublicationStatus;
use App\Nova\Resource;
use App\Nova\Resources\User;
use Benjacho\BelongsToManyField\BelongsToManyField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Modules\Learning\Models\Course as CourseModel;
use Modules\Learning\Nova\Filters\CourseTeacher;
use Modules\Learning\Nova\Filters\FinishedAt;
use Modules\Learning\Nova\Filters\StartedAt;
use Modules\Learning\Nova\Flexible\Presets\CourseContent;
use Modules\Learning\Nova\Resources\Attachment\Attachment;
use Modules\Learning\Nova\Resources\Lesson\Homework\Homework;
use Modules\Learning\Nova\Resources\Lesson\Lesson;
use Modules\Learning\Nova\Resources\Quiz\Quiz;
use R64\NovaImageCropper\ImageCropper;
use Titasgailius\SearchRelations\SearchesRelations;
use Whitecube\NovaFlexibleContent\Flexible;

/**
 * Class Course
 * @package Modules\Learning\Nova\Resources
 */
class Course extends Resource
{
    use SearchesRelations;

    /**
     * @var string
     */
    public static $group = 'Обучение';

    /**
     * @var int
     */
    public static int $priority = 0;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \Modules\Learning\Models\Course::class;

    /**
     * @var string[]
     */
    public static $with = ['teachers'];

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
        'teachers' => ['name', 'email'],
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

            Boolean::make('Опубликован', 'published')
                ->rules(['required', 'boolean'])
                ->default(false)
                ->sortable(),

            ImageCropper::make('Превью', 'image')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->disk('images'),

            Text::make('Название', 'name')
                ->rules(['required', 'string'])
                ->sortable(),

            Flexible::make('Описание', 'content')
                ->preset(CourseContent::class),

            ImageCropper::make('Превью', 'image')
                ->onlyOnForms()
                ->aspectRatio(false)
                ->disk('images')
                ->path(CourseModel::IMAGES_FOLDER)
                ->preview(function () {
                    if (! $this->image) { return null; }
                    $url = Storage::disk('images')->url("{$this->image}");
                    $filetype = pathinfo($url)['extension'];
                    return 'data:image/' . $filetype . ';base64,' . base64_encode(file_get_contents($url));
                }),

            BelongsToManyField::make('Преподаватели', 'teachers', User::class)
                ->options(function () {
                    return UserModel::where('is_admin', true)->get();
                })
                ->rules(['required', 'min:1'])
                ->optionsLabel('name')
                ->canSelectAll('Выбрать всех', false)
                ->hideFromDetail()
                ->hideFromIndex(),

            BelongsToMany::make('Преподаватели', 'teachers', User::class)
                ->onlyOnDetail(),

            DateTime::make('Дата старта', 'started_at')
                ->rules(['nullable', 'date', 'required_with:finished_at'])
                ->nullable()
                ->sortable()
                ->help('Оставьте поле пустым, если курс бессрочный'),

            DateTime::make('Дата завершения', 'finished_at')
                ->rules(['nullable', 'date', 'after:started_at'])
                ->nullable()
                ->sortable()
                ->help('Оставьте поле пустым, если курс бессрочный'),

            DateTime::make('Дата закрытия доступа', 'access_closed_at')
                ->rules(['nullable', 'date', 'after:finished_at'])
                ->nullable()
                ->sortable()
                ->help('Можно указать, если доступ к курсу останется ещё какое-то время после завершения'),

            HasMany::make('Занятия', 'allLessons', Lesson::class),

            HasMany::make('Домашние задания', 'allHomeworks', Homework::class),

            HasMany::make('Ма териалы', 'attachments', Attachment::class),

            HasMany::make('Тесты', 'allQuizzes', Quiz::class),

            BelongsToMany::make('Студенты', 'students', Student::class)
                ->fields(function () {
                    return [
                        Date::make('Дата зачисления', 'started_at')
                            ->nullable()
                            ->sortable(),

                        Date::make('Дата отчисления', 'finished_at')
                            ->nullable()
                            ->sortable(),
                    ];
                }),
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
            new CourseTeacher,
            new StartedAt,
            new FinishedAt,
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
        return 'Курсы';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Курс';
    }
}
