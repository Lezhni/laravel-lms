<?php

namespace Modules\Learning\Nova\Resources;

use App\Models\User as UserModel;
use App\Nova\Resource;
use App\Services\CountriesService;
use Hubertnnn\LaravelNova\Fields\DynamicSelect\DynamicSelect;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ResourceIndexRequest;
use Laravel\Nova\Panel;
use Modules\Learning\Nova\Actions\EnrollStudents;
use Modules\Notifications\Nova\Actions\NotifyStudents;
use Modules\Statistics\Nova\Actions\ExportCourseStudents;
use R64\NovaImageCropper\ImageCropper;

/**
 * Class Student
 * @package App\Nova\Resources
 */
class Student extends Resource
{
    /**
     * @var string
     */
    public static $group = 'Обучение - прогресс';

    /**
     * @var int
     */
    public static int $priority = 1;

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
        'id', 'name', 'email', 'phone',
    ];

    /**
     * @var int
     */
    public static $perPageViaRelationship = 999;

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
                ->hideWhenUpdating()
                ->hideFromIndex(function (ResourceIndexRequest $request) {
                    return $request->viaRelationship();
                }),

            Text::make('Имя', 'name')
                ->sortable()
                ->rules(['required', 'max:255']),

            Text::make('Email')
                ->sortable()
                ->rules(['required', 'email', 'max:255'])
                ->creationRules(['unique:users,email'])
                ->updateRules(['unique:users,email,{{resourceId}}']),

            Text::make('Телефон', 'phone')
                ->sortable()
                ->rules(['nullable', 'phone:AUTO', 'max:255'])
                ->creationRules(['unique:users,phone'])
                ->updateRules(['unique:users,phone,{{resourceId}}'])
                ->hideFromIndex(function (ResourceIndexRequest $request) {
                    return $request->viaRelationship();
                }),

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

            Text::make('Последняя активность', 'last_activity_at')
                ->resolveUsing(function () {
                    return $this->last_activity_at?->diffForHumans() ?? null;
                })
                ->exceptOnForms(),

            Panel::make('Дополнительная информация', $this->extraFields()),
        ];
    }

    /**
     * @return array
     */
    protected function extraFields(): array
    {
        $countriesService = App::make(CountriesService::class);
        $countries = $countriesService->getList();

        $countriesValues = [];
        foreach ($countries as $country) {
            $countriesValues[$country] = $country;
        }

        $sexValues = [];
        foreach (UserModel::SEX_LIST as $sex) {
            $sexValues[$sex] = $sex;
        }

        return [
            Select::make('Пол', 'sex')
                ->options($sexValues)
                ->rules(['nullable', Rule::in(UserModel::SEX_LIST)])
                ->hideFromIndex(),

            DynamicSelect::make('Страна', 'country')
                ->options($countriesValues)
                ->rules(['nullable', 'string'])
                ->nullable()
                ->hideFromIndex(),

            DynamicSelect::make('Город', 'city')
                ->dependsOn(['country'])
                ->options(function ($values) use ($countriesService) {
                    $country = Arr::get($values, 'country');
                    if (!$country) {
                        return [];
                    }

                    $citiesValues = [];
                    $cities = $countriesService->getCities($country);
                    foreach ($cities as $city) {
                        $citiesValues[$city] = $city;
                    }

                    return $citiesValues;
                })
                ->rules(['nullable', 'string'])
                ->nullable()
                ->hideFromIndex(),

            Text::make('Логин Telegram', 'telegram')
                ->rules(['nullable', 'string'])
                ->nullable()
                ->hideFromIndex(),

            HasMany::make('Обучается на курсах', 'enrollments', Enrollment::class),
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
            new EnrollStudents,
            new NotifyStudents,
            (new ExportCourseStudents)->canSee(function (NovaRequest $request) {
                return $request->viaRelationship();
            }),
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Студенты';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Студент';
    }
}
