<?php

namespace App\Nova\Resources\ACL;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

/**
 * Class Permission
 * @package App\Nova\ACL
 */
class Permission extends Resource
{
    /**
     * @var string
     */
    public static $group = 'Администратору';

    /**
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Spatie\Permission\Models\Permission::class;

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
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')
                ->sortable(),

            Text::make('Название', 'title')
                ->rules(['required', 'string'])
                ->sortable(),

            Text::make('Алиас', 'name')
                ->rules(['required', 'unique:users,email'])
                ->hideWhenUpdating()
                ->sortable(),
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Разрешения';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Разрешение';
    }
}
