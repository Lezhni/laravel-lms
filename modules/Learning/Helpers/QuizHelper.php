<?php

namespace Modules\Learning\Helpers;

use App\Helpers\BlockEditor\Helper;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Modules\Learning\Models\Quiz\Answer;
use Modules\Learning\Models\Quiz\Question;
use Modules\Learning\Models\Quiz\Quiz;
use Modules\Learning\Models\Quiz\QuizResult;

/**
 *
 */
class QuizHelper
{
    /**
     * @param \App\Helpers\BlockEditor\Helper $contentHelper
     */
    public function __construct(protected Helper $contentHelper)
    {
    }

    /**
     * @param \Modules\Learning\Models\Quiz\Quiz $quiz
     * @param \App\Models\User $student
     * @return \Modules\Learning\Models\Quiz\Quiz
     */
    public function formatQuiz(Quiz $quiz, User $student): Quiz
    {
        $quiz->questions = $this->formatQuestions($quiz->questions);

        $quiz->completed = QuizResult::query()
            ->where('student_id', $student->id)
            ->whereIn('question_id', $quiz->questions->pluck('id'))
            ->exists();

        return $quiz;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $questions
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function formatQuestions(EloquentCollection $questions): EloquentCollection
    {
        return $questions->map(function (Question $question) {
            $correctAnswersCount = $question->correctAnswers()->count();
            $question->multiple = ($correctAnswersCount > 1);

            $content = $question->content;
            $question->content = $this->contentHelper->prepareContent($content);

            return $question;
        });
    }

    /**
     * @param \Modules\Learning\Models\Quiz\Quiz $quiz
     * @param \Illuminate\Database\Eloquent\Collection $quizResults
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function fillQuizWithResults(Quiz $quiz, EloquentCollection $quizResults): EloquentCollection|Collection
    {
        $quiz->questions = $this->formatQuestions($quiz->questions);

        return $quiz->questions->map(function (Question $question) use ($quizResults) {
            $question->answers->map(function(Answer $answer) use ($question, $quizResults) {
                $studentQuestionResult = $quizResults->where('question_id', $question->id)->first();
                if (!$studentQuestionResult instanceof QuizResult) {
                    $answer->selected_by_student = false;
                    return $answer;
                }

                $studentAnswersIds = $studentQuestionResult->answers->pluck('id');
                $answer->selected_by_student = $studentAnswersIds->contains($answer->id);
                return $answer;
            });

            return $question;
        });
    }
}