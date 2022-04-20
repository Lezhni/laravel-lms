<?php

namespace Modules\Learning\Nova\Resources\Lesson\Homework;

use App\Nova\Resource;
use CreaceptLms\HomeworkChat\HomeworkChat;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Panel;
use Modules\Learning\Nova\Filters\HomeworkGradeStatus;
use Modules\Learning\Nova\Filters\OnlyCurrentTeacherCourses;
use Modules\Learning\Nova\Resources\Course;
use Modules\Learning\Nova\Resources\Lesson\Lesson;
use Modules\Learning\Nova\Resources\Student;
use Titasgailius\SearchRelations\SearchesRelations;

/**
 * Class Grade
 * @package Modules\Learning\Nova\Resources\Lesson\Homework
 */
class Grade extends Resource
{
    use SearchesRelations;

    /**
     * @var string
     */
    public static $group = 'Обучение - прогресс';

    /**
     * @var int
     */
    public static int $priority = 99;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \Modules\Learning\Models\Lesson\Homework\Grade::class;

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
        'lesson' => ['name'],
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
     * @var string[]
     */
    public static $with = ['student', 'homework'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request): array
    {
        $homeworkMaxGrade = $this->homework?->max_grade;
        $gradeId = $this->id;

        return [
            ID::make(__('ID'), 'id')
                ->sortable(),

            BelongsTo::make('Студент', 'student', Student::class)
                ->readonly(true)
                ->sortable(),

            BelongsTo::make('Курс', 'course', Course::class)
                ->readonly(true)
                ->sortable(),

            BelongsTo::make('Занятие', 'lesson', Lesson::class)
                ->readonly(true)
                ->sortable(),

            Number::make('Оценка за задание', 'grade')
                ->min(0)
                ->max($homeworkMaxGrade)
                ->rules(['required', 'integer', 'min:0', "max:{$homeworkMaxGrade}"])
                ->sortable()
                ->help("Максимальная оценка за задание: {$homeworkMaxGrade}"),

            Panel::make('История чата', function () use ($gradeId) {
                return [
                    HomeworkChat::make('История чата')
                        ->grade($gradeId)
                        ->hideFromIndex()
                        ->readonly()
                        ->nullable(),
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
            new OnlyCurrentTeacherCourses,
            new HomeworkGradeStatus,
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
        return [];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Проверка ДЗ';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Проверка ДЗ';
    }
}
