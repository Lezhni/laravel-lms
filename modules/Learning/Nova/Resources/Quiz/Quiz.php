<?php

namespace Modules\Learning\Nova\Resources\Quiz;

use App\Nova\Actions\PublishResource;
use App\Nova\Actions\UnpublishResource;
use App\Nova\Filters\PublicationStatus;
use App\Nova\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Learning\Models\Lesson\Lesson as LessonModel;
use Modules\Learning\Nova\Filters\Lesson as LessonFilter;
use Modules\Learning\Nova\Resources\Lesson\Lesson;
use Titasgailius\SearchRelations\SearchesRelations;

/**
 * Class Quiz
 * @package Modules\Learning\Nova\Resources\Quiz
 */
class Quiz extends Resource
{
    use SearchesRelations;

    /**
     * @var string
     */
    public static $group = 'Тесты';

    /**
     * @var int
     */
    public static int $priority = 0;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \Modules\Learning\Models\Quiz\Quiz::class;

    /**
     * @var string[]
     */
    public static $with = ['questions', 'lesson.course'];

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
                ->default(true)
                ->sortable(),

            BelongsTo::make('Занятие', 'lesson', Lesson::class)
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            Select::make('Занятие', 'lesson_id')
                ->options(function () {
                    $options = [];
                    $lessons = LessonModel::published()->with('course')->get();
                    foreach ($lessons as $lesson) {
                        $options[$lesson->id] = ['label' => $lesson->name, 'group' => $lesson->course->name];
                    }
                    return $options;
                })
                ->rules(['required', 'exists:lessons,id'])
                ->onlyOnForms()
                ->sortable(),

            Text::make('Название', 'name')
                ->rules(['required', 'string'])
                ->sortable(),

            BelongsTo::make('Открывать тест после прохождения:', 'previousQuiz', Quiz::class)
                ->rules(['nullable', 'exists:quizzes,id'])
                ->hideWhenCreating()
                ->nullable(),

            HasMany::make('Вопросы', 'questions', Question::class),
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
        return 'Тесты';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Тест';
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuizzes(NovaRequest $request, Builder $query): Builder
    {
        $mode = $request->get('editMode');
        if ($mode !== 'update') {
            return parent::relatableQuery($request, $query);
        }

        $currentQuiz = $request->findModelOrFail();
        $currentQuiz->load('lesson.course');
        $course = $currentQuiz->lesson->course;

        return $query
            ->where('id', '!=', $currentQuiz->id)
            ->whereHas('course', function (Builder $q) use ($course) {
                $q->where('courses.id', $course->id);
            });
    }
}
