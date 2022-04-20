<?php

namespace Modules\Learning\Models\Quiz;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * @property \App\Models\User student
 * @property \Modules\Learning\Models\Quiz\Quiz quiz
 * @property \Modules\Learning\Models\Quiz\Question question
 * @property \Illuminate\Database\Eloquent\Collection answers
 */
class QuizResult extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    /**
     * @var string
     */
    protected $table = 'quizzes_results';

    /**
     * @var string[]
     */
    protected $fillable = [
        'student_id',
        'question_id',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'student_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id')
            ->select(['id', 'name', 'email']);
    }

    /**
     * @return \Znck\Eloquent\Relations\BelongsToThrough
     */
    public function quiz(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Quiz::class,
            Question::class,
            null,
            '',
            [Question::class => 'question_id']
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id')
            ->select(['id', 'name']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'quizzes_results_answers', 'quiz_result_id');
    }
}