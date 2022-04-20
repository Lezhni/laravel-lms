<?php

namespace Modules\Learning\Models\Lesson\Homework;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Lesson\Lesson;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * @property \Modules\Learning\Models\Lesson\Homework\Homework homework
 * @property \Modules\Learning\Models\Lesson\Lesson lesson
 * @property \App\Models\User student
 * @property \Illuminate\Database\Eloquent\Collection messages
 */
class Grade extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    /**
     * @var string
     */
    protected $table = 'lessons_homeworks_grades';

    /**
     * @var string[]
     */
    protected $fillable = [
        'grade',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'student_id',
        'homework_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function homework(): BelongsTo
    {
        return $this->belongsTo(Homework::class, 'homework_id');
    }

    /**
     * @return \Znck\Eloquent\Relations\BelongsToThrough
     */
    public function lesson(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Lesson::class,
            Homework::class,
            null,
            '',
            [Homework::class => 'homework_id']
        );
    }

    /**
     * @return \Znck\Eloquent\Relations\BelongsToThrough
     */
    public function course(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Course::class,
            [Lesson::class, Homework::class],
            null,
            '',
            [Lesson::class => 'lesson_id', Homework::class => 'homework_id']
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'grade_id');
    }
}
