<?php

namespace Modules\Learning\Nova\Resources\Attachment;

use App\Nova\Resource;
use Benjacho\BelongsToManyField\BelongsToManyField;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Learning\Helpers\AttachmentTypes;
use Modules\Learning\Models\Attachment\Attachment as AttachmentModel;
use Modules\Learning\Models\Group\Group as GroupModel;
use Modules\Learning\Nova\Filters\Course as CourseFilter;
use Modules\Learning\Nova\Resources\Course;
use Modules\Learning\Nova\Resources\Group\Group;
use Modules\Pages\Models\Page;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;
use Titasgailius\SearchRelations\SearchesRelations;

/**
 * Class Attachment
 * @package Modules\Learning\Nova\Resources\Attachment
 */
class Attachment extends Resource
{
    use SearchesRelations, HasSortableRows;

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
    public static string $model = AttachmentModel::class;

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
        'id', 'name', 'link',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static array $searchRelations = [
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
            ID::make(__('ID'), 'id')
                ->sortable(),

            Text::make('Название', 'name')
                ->rules(['required', 'string', 'max:255'])
                ->sortable(),

            Textarea::make('Описание', 'description')
                ->rules(['nullable', 'string', 'max:65535'])
                ->hideFromIndex(),

            Select::make('Тип материала', 'type')
                ->options(AttachmentTypes::ALL)
                ->default(AttachmentTypes::TYPE_LINK)
                ->displayUsingLabels(),

            NovaDependencyContainer::make([
                Text::make('Выбранная страница', 'link')
                    ->resolveUsing(function () {
                        if (!$this->link) return null;
                        return "<a href='{$this->link}' class='no-underline dim text-primary' target='_blank'>{$this->link}</a>";
                    })
                    ->asHtml(),
            ])
                ->onlyOnDetail()
                ->dependsOn('type', AttachmentTypes::TYPE_PAGE),

            NovaDependencyContainer::make([
                Select::make('Выбранная страница', 'link')
                    ->options(function () {
                        return Page::select(['name', 'alias'])
                            ->get()
                            ->mapWithKeys(function (Page $page) {
                                return [route('page', $page->alias) => $page->name];
                            });
                    })
                    ->rules(['required']),
            ])
                ->onlyOnForms()
                ->dependsOn('type', AttachmentTypes::TYPE_PAGE),

            NovaDependencyContainer::make([
                Text::make('Ссылка', 'link')
                    ->resolveUsing(function () {
                        if (!$this->link) return null;
                        return "<a href='{$this->link}' class='no-underline dim text-primary' target='_blank'>{$this->link}</a>";
                    })
                    ->asHtml(),
            ])
                ->onlyOnDetail()
                ->dependsOn('type', AttachmentTypes::TYPE_LINK)
                ->dependsOn('type', AttachmentTypes::TYPE_VIDEO),

            NovaDependencyContainer::make([
                Text::make('Ссылка', 'link')
                    ->rules(['required', 'url', 'max:255']),
            ])
                ->onlyOnForms()
                ->dependsOn('type', AttachmentTypes::TYPE_LINK)
                ->dependsOn('type', AttachmentTypes::TYPE_VIDEO),

            NovaDependencyContainer::make([
                Text::make('Ссылка на файл', 'link')
                    ->resolveUsing(function () {
                        if (!$this->link) return null;
                        $fileLink = Storage::disk('uploads')->url($this->link);
                        return "<a href='{$fileLink}' class='no-underline dim text-primary' target='_blank'>{$fileLink}</a>";
                    })
                    ->asHtml(),
            ])
                ->onlyOnDetail()
                ->dependsOn('type', AttachmentTypes::TYPE_FILE),

            NovaDependencyContainer::make([
                File::make('Файл', 'link')
                    ->disk('uploads')
                    ->path('attachments')
                    ->creationRules(['required', 'file'])
                    ->updateRules(function (NovaRequest $request) {
                        $model = $request->findModelOrFail();
                        return $model->link ? [] : ['required', 'file'];
                    })
            ])
                ->onlyOnForms()
                ->dependsOn('type', AttachmentTypes::TYPE_FILE),

            BelongsTo::make('Категория', 'category', Category::class)
                ->rules(['nullable', 'exists:courses_attachment_categories,id'])
                ->showCreateRelationButton()
                ->nullable()
                ->sortable(),

            BelongsTo::make('Курс', 'course', Course::class)
                ->rules(['required', 'exists:courses,id'])
                ->sortable(),

            BelongsToManyField::make('Относится к потокам:', 'groups', Group::class)
                ->options(function () {
                    return GroupModel::select(['id', 'name'])->get();
                })
                ->rules(['nullable', 'min:1'])
                ->optionsLabel('name')
                ->canSelectAll('Выбрать все')
                ->hideFromDetail(),
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
            new CourseFilter,
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Материалы курсов';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Материал курса';
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey(): string
    {
        return 'courses-attachments';
    }
}
