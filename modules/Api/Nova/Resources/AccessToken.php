<?php

namespace Modules\Api\Nova\Resources;

use App\Nova\Actions\PublishResource;
use App\Nova\Actions\UnpublishResource;
use App\Nova\Filters\PublicationStatus;
use App\Nova\Resource;
use App\Nova\Resources\ACL\Permission;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use ZiffMedia\NovaSelectPlus\SelectPlus;

/**
 * Class AccessToken
 * @package Modules\Api\Nova\Resources
 */
class AccessToken extends Resource
{
    /**
     * @var string
     */
    public static $group = 'Администратору';

    /**
     * @var int
     */
    public static int $priority = 0;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \Modules\Api\Models\AccessToken::class;

    /**
     * @var string[]
     */
    public static $with = ['permissions'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title',
    ];

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
                ->hideFromIndex()
                ->sortable(),

            Boolean::make('Опубликован', 'published')
                ->rules(['required', 'boolean'])
                ->default(false)
                ->sortable(),

            Text::make('Название', 'title')
                ->rules(['required', 'string'])
                ->sortable(),

            Text::make('Ключ', 'key')
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            SelectPlus::make('Разрешения', 'permissions', Permission::class)
                ->rules(['required'])
                ->label('title')
                ->usingIndexLabel('title')
                ->usingDetailLabel('title'),
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
        return 'Ключи доступа API';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Ключ доступа API';
    }
}
