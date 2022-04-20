<?php

namespace Modules\Learning\Nova\Resources\Quiz;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Modules\Learning\Nova\Filters\Quiz as QuizFilter;
use Modules\Learning\Nova\Flexible\Presets\QuizQuestionContent;
use Titasgailius\SearchRelations\SearchesRelations;
use Whitecube\NovaFlexibleContent\Flexible;

/**
 * Class Question
 * @package Modules\Learning\Nova\Resources\Quiz
 */
class Question extends Resource
{
    use SearchesRelations;

    /**
     * @var string
     */
    public static $group = 'Тесты';

    /**
     * @var int
     */
    public static int $priority = 1;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \Modules\Learning\Models\Quiz\Question::class;

    /**
     * @var string[]
     */
    public static $with = ['answers'];

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
        'quiz' => ['name'],
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

            BelongsTo::make('Тест', 'quiz', Quiz::class)
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            Select::make('Тест', 'quiz_id')
                ->options(function () {
                    $options = [];
                    $quizzes = \Modules\Learning\Models\Quiz\Quiz::published()->with('lesson.course')->get();
                    foreach ($quizzes as $quiz) {
                        $options[$quiz->id] = ['label' => $quiz->name, 'group' => $quiz->lesson->name];
                    }
                    return $options;
                })
                ->rules(['required', 'exists:quizzes,id'])
                ->onlyOnForms()
                ->sortable(),

            Text::make('Текст вопроса', 'name')
                ->rules(['required', 'string'])
                ->sortable(),

            Flexible::make('Контент', 'content')
                ->preset(QuizQuestionContent::class),

            HasMany::make('Варианты ответов', 'answers', Answer::class),
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
            new QuizFilter,
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Вопросы';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Вопрос';
    }
}
