<?php

namespace Modules\Learning\Models\Lesson\Homework;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Learning\Models\Lesson\Lesson;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * @property \App\Models\User sender
 * @property \Modules\Learning\Models\Lesson\Homework\Grade grade
 * @property \Modules\Learning\Models\Lesson\Lesson lesson
 * @property \Illuminate\Database\Eloquent\Collection attachments
 */
class ChatMessage extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    /**
     * @var string
     */
    protected $table = 'lessons_homeworks_messages';

    /**
     * @var string[]
     */
    protected $hidden = [
        'sender_id',
        'grade_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this
            ->belongsTo(User::class, 'sender_id')
            ->select(['id', 'avatar', 'name', 'is_admin']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    /**
     * @return \Znck\Eloquent\Relations\BelongsToThrough
     */
    public function lesson(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Lesson::class,
            [Homework::class, Grade::class],
            null,
            '',
            [Homework::class => 'homework_id', Grade::class => 'grade_id']
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments(): HasMany
    {
        return $this
            ->hasMany(ChatAttachment::class, 'message_id')
            ->select(['message_id', 'filename']);
    }
}
