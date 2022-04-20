<?php

namespace Modules\Learning\Nova\Resources\Lesson\Attachment;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Modules\Learning\Models\Lesson\Attachment\Category as CategoryModel;

class Category extends Resource
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
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = CategoryModel::class;

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
                ->rules(['required', 'string', 'max:255'])
                ->sortable(),

            HasMany::make('Материалы', 'attachments', Attachment::class),
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
        return [];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Категории материалов занятий';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Категория материалов занятий';
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey(): string
    {
        return 'lessons-attachment-categories';
    }
}