<?php

namespace Modules\Learning\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \Illuminate\Database\Eloquent\Collection answers
 * @property \Illuminate\Database\Eloquent\Collection correctAnswers
 */
class Question extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'quizzes_questions';

    /**
     * @var string[]
     */
    protected $hidden = [
        'quiz_id',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function correctAnswers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id')
            ->where('is_correct', true);
    }

    /**
     * @param string|array|null $value
     * @return array
     */
    public function getContentAttribute($value): array
    {
        if (!is_string($value)) {
            return [];
        }

        return json_decode($value, true);
    }
}
