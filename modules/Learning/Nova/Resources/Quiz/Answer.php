<?php

namespace Modules\Learning\Nova\Resources\Quiz;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Modules\Learning\Nova\Filters\QuizQuestion;
use Titasgailius\SearchRelations\SearchesRelations;

/**
 * Class Answer
 * @package Modules\Learning\Nova\Resources\Quiz
 */
class Answer extends Resource
{
    use SearchesRelations;

    /**
     * @var string
     */
    public static $group = 'Тесты';

    /**
     * @var int
     */
    public static int $priority = 2;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \Modules\Learning\Models\Quiz\Answer::class;

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
        'question' => ['name'],
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

            BelongsTo::make('Вопрос', 'question', Question::class)
                ->showCreateRelationButton()
                ->required()
                ->sortable(),

            Text::make('Текст ответа', 'name')
                ->rules(['required', 'string'])
                ->sortable(),

            Boolean::make('Этот ответ верный?', 'is_correct')
                ->rules(['required', 'boolean'])
                ->default(false)
                ->help('У одного вопроса может быть несколько верных ответов'),
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
            new QuizQuestion,
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Ответы к вопросам';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Ответ';
    }
}
