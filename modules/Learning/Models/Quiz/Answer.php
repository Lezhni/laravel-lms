<?php

namespace Modules\Learning\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class Answer extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'quizzes_answers';

    /**
     * @var string[]
     */
    protected $hidden = [
        'question_id',
        'pivot',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
