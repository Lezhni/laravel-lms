<?php

namespace Modules\Pages\Nova\Resources;

use App\Nova\Actions\PublishResource;
use App\Nova\Actions\UnpublishResource;
use App\Nova\Filters\PublicationStatus;
use App\Nova\Flexible\Presets\BlockEditor;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Titasgailius\SearchRelations\SearchesRelations;
use Whitecube\NovaFlexibleContent\Flexible;

class Page extends Resource
{
    use SearchesRelations;

    /**
     * @var string
     */
    public static $group = 'Страницы';

    /**
     * @var int
     */
    public static int $priority = 1;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \Modules\Pages\Models\Page::class;

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
        'id', 'name', 'alias',
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

            Boolean::make('Опубликован', 'published')
                ->rules(['required', 'boolean'])
                ->default(false)
                ->sortable(),

            BelongsTo::make('Категория', 'category', Category::class)
                ->nullable(),

            Text::make('Название', 'name')
                ->rules(['required', 'string', 'max:255'])
                ->sortable(),

            Text::make('Ссылка на страницу', function () {
                $link = url("pages/{$this->alias}");
                return "<a href='{$link}' class='no-underline dim text-primary font-bold' target='_blank'>{$link}</a>";
            })
                ->asHtml()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Text::make('Алиас', 'alias')
                ->rules(['nullable', 'string', 'unique:pages,alias,{{resourceId}}'])
                ->hideFromIndex()
                ->help('Только латиница. Если оставить поле пустым, будет сгенерирован из названия'),

            Flexible::make('Контент', 'content')
                ->preset(BlockEditor::class),
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
        return 'Страницы';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Страница';
    }
}
