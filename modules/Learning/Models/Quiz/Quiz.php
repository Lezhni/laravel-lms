<?php

namespace Modules\Learning\Models\Quiz;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Lesson\Lesson;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * @property \Illuminate\Database\Eloquent\Collection questions
 */
class Quiz extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'quizzes';

    /**
     * @var string[]
     */
    protected $hidden = [
        //'lesson_id',
        'previous_quiz_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'published' => 'boolean',
    ];

    /**
     * @return \Znck\Eloquent\Relations\BelongsToThrough
     */
    public function course(): BelongsToThrough
    {
        return $this->belongsToThrough(Course::class, Lesson::class);
    }

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
    public function previousQuiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'previous_quiz_id')
            ->select(['id', 'name'])
            ->published();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'quiz_id');
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
