<?php

namespace Modules\Learning\Models\Lesson\Homework;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Learning\Models\Lesson\Lesson;

/**
 * @property \Modules\Learning\Models\Lesson\Lesson lesson
 * @property \Illuminate\Database\Eloquent\Collection messages
 */
class Homework extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'lessons_homeworks';

    /**
     * @var string[]
     */
    protected $hidden = [
        'lesson_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'published' => 'boolean',
        'content' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'homework_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function messages(): HasManyThrough
    {
        return $this
            ->hasManyThrough(ChatMessage::class, Grade::class, 'homework_id', 'grade_id')
            ->select([
                'lessons_homeworks_messages.id',
                'lessons_homeworks_messages.created_at',
                'sender_id',
                'grade_id',
                'message',
            ]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}
