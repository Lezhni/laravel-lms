<?php

namespace App\Nova\Resources\ACL;

use App\Nova\Resource;
use Benjacho\BelongsToManyField\BelongsToManyField;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use ZiffMedia\NovaSelectPlus\SelectPlus;

/**
 * Class Role
 * @package App\Nova\ACL
 */
class Role extends Resource
{
    /**
     * @var string
     */
    public static $group = 'Администратору';

    /**
     * @var int
     */
    public static int $priority = 1;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \Spatie\Permission\Models\Role::class;

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
        'id', 'name', 'title',
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
                ->sortable(),

            Text::make('Название', 'title')
                ->rules(['required', 'string'])
                ->sortable(),

            Text::make('Алиас', 'name')
                ->rules(['required', 'unique:roles,name'])
                ->readonly(function() { return $this->resource->exists; })
                ->sortable(),

            BelongsToManyField::make('Разрешения', 'permissions', Permission::class)
                ->rules(['required', 'min:1'])
                ->optionsLabel('title')
                ->canSelectAll('Выбрать все'),
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Роли';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Роль';
    }
}