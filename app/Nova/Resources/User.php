<?php

namespace App\Nova\Resources;

use App\Models\User as UserModel;
use App\Nova\Resource;
use App\Nova\Resources\ACL\Role;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use R64\NovaImageCropper\ImageCropper;
use ZiffMedia\NovaSelectPlus\SelectPlus;

/**
 * Class User
 * @package App\Nova\Resources
 */
class User extends Resource
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
    public static string $model = UserModel::class;

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
        'id', 'name', 'email',
    ];

    /**
     * Build an "index" query for the given resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_admin', true);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),

            ImageCropper::make('Фото профиля', 'avatar')
                ->aspectRatio(1)
                ->avatar()
                ->disk('uploads')
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Text::make('Имя', 'name')
                ->sortable()
                ->rules(['required', 'max:255']),

            Text::make('Email')
                ->sortable()
                ->rules(['required', 'email', 'max:255'])
                ->creationRules(['unique:users,email'])
                ->updateRules(['unique:users,email,{{resourceId}}']),

            ImageCropper::make('Фото профиля', 'avatar')
                ->aspectRatio(1)
                ->avatar()
                ->disk('uploads')
                ->path(UserModel::IMAGES_FOLDER)
                ->preview(function () {
                    if (!$this->avatar) {
                        return null;
                    }
                    $filetype = pathinfo($this->avatarUrl)['extension'];
                    return 'data:image/' . $filetype . ';base64,' . base64_encode(file_get_contents($this->avatarUrl));
                })
                ->onlyOnForms()
                ->nullable(),

            Password::make('Пароль', 'password')
                ->onlyOnForms()
                ->rules(['string', 'min:8', 'max:255'])
                ->creationRules(['required'])
                ->updateRules(['nullable']),

            SelectPlus::make('Роли', 'roles', Role::class)
                ->rules(['required'])
                ->label('title')
                ->usingIndexLabel('title')
                ->usingDetailLabel('title'),

            Hidden::make('Администратор', 'is_admin')
                ->default(1),
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Админы, учителя, менеджеры';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Пользователь';
    }
}
