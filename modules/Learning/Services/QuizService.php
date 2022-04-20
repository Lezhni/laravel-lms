<?php

namespace Modules\Learning\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Modules\Learning\Models\Quiz\QuizResult;
use Modules\Learning\Repositories\QuizRepository;

/**
 *
 */
class QuizService
{
    /**
     * @param \Modules\Learning\Repositories\QuizRepository $quizRepository
     */
    public function __construct(protected QuizRepository $quizRepository)
    {
    }

    public function isQuizCompleted(int $quizId, int $studentId): bool
    {
        return QuizResult::query()
            ->where('student_id', $studentId)
            ->whereHas('question', function (Builder $q) use ($quizId) {
                $q->where('id', $quizId);
            })
            ->exists();
    }

    /**
     * @param \Illuminate\Support\Collection $rawQuizResults
     * @param \App\Models\User $student
     * @param int $quizId
     * @throws \Throwable
     */
    public function examQuizResults(SupportCollection $rawQuizResults, User $student, int $quizId)
    {
        DB::transaction(function () use ($rawQuizResults, $student, $quizId) {
            $this->quizRepository->clearQuizResults($student, $quizId);
            $this->quizRepository->storeQuizResults($rawQuizResults, $student);
        });
    }
}