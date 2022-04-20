<?php

namespace Modules\Learning\Policies\Quiz;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Learning\Models\Quiz\Question;

/**
 * Class QuestionPolicy
 * @package Modules\Learning\Policies\Quiz
 */
class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('quizzes.list');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Quiz\Question $question
     * @return bool
     */
    public function view(User $user, Question $question): bool
    {
        return $user->can('quizzes.list');
    }

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('quizzes.create');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Quiz\Question $question
     * @return bool
     */
    public function update(User $user, Question $question): bool
    {
        return $user->can('quizzes.update');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Quiz\Question $question
     * @return bool
     */
    public function delete(User $user, Question $question): bool
    {
        return $user->can('quizzes.delete');
    }
}