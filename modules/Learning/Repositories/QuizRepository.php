<?php

namespace Modules\Learning\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Modules\Learning\Models\Quiz\Question;
use Modules\Learning\Models\Quiz\Quiz;
use Modules\Learning\Models\Quiz\QuizResult;

/**
 *
 */
class QuizRepository
{
    /**
     * @param int $quizId
     * @return \Modules\Learning\Models\Quiz\Quiz|null
     */
    public function getQuiz(int $quizId): ?Quiz
    {
        return Quiz::published()
            ->select(['id', 'name', 'previous_quiz_id'])
            ->with(['questions.answers', 'previousQuiz'])
            ->find($quizId);
    }

    /**
     * @param int $quizId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getQuizQuestions(int $quizId): Collection
    {
        return Question::query()
            ->with('answers')
            ->where('quiz_id', $quizId)
            ->get();
    }

    /**
     * @param int $quizId
     * @param int $studentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getQuizResults(int $quizId, int $studentId): Collection
    {
        return QuizResult::query()
            ->with('answers')
            ->whereHas('quiz', function ($query) use ($quizId) { $query->where('quizzes.id', $quizId); })
            ->where('student_id', $studentId)
            ->get();
    }

    /**
     * @param \Illuminate\Support\Collection $rawQuizResults
     * @param \App\Models\User $student
     * @throws \Throwable
     */
    public function storeQuizResults(SupportCollection $rawQuizResults, User $student)
    {
        $rawQuizResults->each(function ($result) use ($student) {
            $studentAnswer = new QuizResult;
            $studentAnswer->student()->associate($student);
            $studentAnswer->question()->associate($result['question']);

            $studentAnswer->saveOrFail();
            $studentAnswer->answers()->attach($result['answers']);
        });
    }

    /**
     * @param \App\Models\User $student
     * @param int $quizId
     */
    public function clearQuizResults(User $student, int $quizId)
    {
        QuizResult::where('student_id', $student->id)
            ->whereHas('quiz', function (Builder $query) use ($quizId) { $query->where('quizzes.id', $quizId); })
            ->delete();
    }
}