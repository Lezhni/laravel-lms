<?php

namespace Modules\Learning\Models\Group;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Learning\Models\Lesson\Lesson;

/**
 * Class GroupLesson
 * @package Modules\Learning\Models\Group
 */
class GroupLesson extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'groups_lessons';

    /**
     * @var string[]
     */
    protected $fillable = [
        'group_id',
        'lesson_id',
        'lesson_link',
        'record_link',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'group_id',
        'lesson_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'started_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
