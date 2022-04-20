<?php

namespace Modules\Learning\Models\Lesson\Attachment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Learning\Helpers\AttachmentTypes;
use Modules\Learning\Models\Lesson\Lesson;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 *
 */
class Attachment extends Model implements Sortable
{
    use SortableTrait;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'lessons_attachments';

    /**
     * @var string[]
     */
    protected $hidden = [
        'lesson_id',
        'category_id',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'type' => AttachmentTypes::TYPE_LINK,
    ];

    /**
     * @var string[]
     */
    public array $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
