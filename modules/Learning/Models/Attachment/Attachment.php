<?php

namespace Modules\Learning\Models\Attachment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Learning\Helpers\AttachmentTypes;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Group\Group;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * Class Attachment
 * @package Modules\Learning\Models\Attachment
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
    protected $table = 'courses_attachments';

    /**
     * @var string[]
     */
    protected $hidden = [
        'course_id',
        'category_id',
        'group_id',
    ];

    /**
     * @var string[]
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
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            Group::class,
            'groups_attachments',
            'attachment_id',
            'group_id',
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
