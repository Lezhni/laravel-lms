<?php

namespace Modules\Learning\Nova\Resources\Lesson\Attachment;

use App\Nova\Resource;
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
use Modules\Learning\Models\Lesson\Attachment\Attachment as AttachmentModel;
use Modules\Learning\Models\Lesson\Lesson as LessonModel;
use Modules\Learning\Nova\Filters\Lesson as LessonFilter;
use Modules\Learning\Nova\Resources\Lesson\Lesson;
use Modules\Pages\Models\Page;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;
use Titasgailius\SearchRelations\SearchesRelations;

/**
 * Class Attachment
 * @package Modules\Learning\Nova\Resources
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
    public static int $priority = 2;

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
        'lesson' => ['name'],
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
                ->rules(['nullable', 'exists:lessons_attachment_categories,id'])
                ->showCreateRelationButton()
                ->nullable()
                ->sortable(),

            BelongsTo::make('Занятие', 'lesson', Lesson::class)
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            Select::make('Занятие', 'lesson_id')
                ->options(function () {
                    $options = [];
                    $lessons = LessonModel::published()->with('course')->get();
                    foreach ($lessons as $lesson) {
                        $options[$lesson->id] = ['label' => $lesson->name, 'group' => $lesson->course->name];
                    }
                    return $options;
                })
                ->rules(['required', 'exists:lessons,id'])
                ->onlyOnForms(),
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
            new LessonFilter,
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Материалы занятий';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Материал занятия';
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey(): string
    {
        return 'lessons-attachments';
    }
}
