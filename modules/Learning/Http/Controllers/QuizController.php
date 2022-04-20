<?php

namespace Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Learning\Helpers\QuizHelper;
use Modules\Learning\Http\Requests\Quiz\QuizRequest;
use Modules\Learning\Models\Quiz\Quiz;
use Modules\Learning\Repositories\QuizRepository;
use Modules\Learning\Services\EnrollmentValidator;
use Modules\Learning\Services\QuizService;
use Throwable;

/**
 *
 */
class QuizController extends Controller
{
    /**
     * @param \Modules\Learning\Helpers\QuizHelper $quizHelper
     * @param \Modules\Learning\Repositories\QuizRepository $quizRepository
     * @param \Modules\Learning\Services\EnrollmentValidator $enrollmentValidator
     * @param \Modules\Learning\Services\QuizService $quizService
     */
    public function __construct(
        protected QuizHelper $quizHelper,
        protected QuizRepository $quizRepository,
        protected EnrollmentValidator $enrollmentValidator,
        protected QuizService $quizService
    )
    {
    }

    /**
     * @param int $courseId
     * @param int $lessonId
     * @param int $quizId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuiz(int $courseId, int $lessonId, int $quizId): JsonResponse
    {
        $student = Auth::user();

        $studentEnrolled = $this->enrollmentValidator->isStudentEnrolled($student->id, $courseId);
        if (!$studentEnrolled) {
            return new JsonResponse([
                'message' => 'У вас нет доступа к выбранному курсу',
            ], 403);
        }

        // TODO (2): Check is quiz assigned to course/lesson

        $quiz = $this->quizRepository->getQuiz($quizId);
        if (!$quiz instanceof Quiz) {
            return new JsonResponse([
                'message' => 'Тест не найден',
            ], 404);
        }

        if (
            $quiz->previousQuiz instanceof Quiz &&
            !$this->quizService->isQuizCompleted($quiz->previousQuiz->id, $student->id)
        ) {
            return new JsonResponse([
                'message' => "Чтобы разблокировать этот тест, сначала пройдите тест \"{$quiz->previousQuiz->name}\"",
            ], 403);
        }

        $quiz = $this->quizHelper->formatQuiz($quiz, $student);

        return new JsonResponse([
            'quiz' => $quiz,
        ]);
    }

    /**
     * @param int $courseId
     * @param int $lessonId
     * @param int $quizId
     * @param \Modules\Learning\Http\Requests\Quiz\QuizRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processQuizResults(int $courseId, int $lessonId, int $quizId, QuizRequest $request): JsonResponse
    {
        $student = Auth::user();

        $studentEnrolled = $this->enrollmentValidator->isStudentEnrolled($student->id, $courseId);
        if (!$studentEnrolled) {
            return new JsonResponse([
                'message' => 'У вас нет доступа к выбранному курсу',
            ], 403);
        }

        // TODO (4): Check is quiz assigned to course/lesson

        $rawQuizAnswers = $request->post('results');
        $rawQuizAnswers = collect($rawQuizAnswers);

        try {
            $this->quizService->examQuizResults($rawQuizAnswers, $student, $quizId);
        } catch (Throwable $e) {
            report($e);
            return new JsonResponse([
                'message' => 'Произошла ошибка, попробуйте еще раз',
            ], 500);
        }

        return new JsonResponse([
            'message' => 'Результаты теста успешно сохранены',
        ]);
    }

    /**
     * @param int $courseId
     * @param int $lessonId
     * @param int $quizId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuizResults(int $courseId, int $lessonId, int $quizId): JsonResponse
    {
        $student = Auth::user();

        $studentEnrolled = $this->enrollmentValidator->isStudentEnrolled($student->id, $courseId);
        if (!$studentEnrolled) {
            return new JsonResponse([
                'message' => 'У вас нет доступа к выбранному курсу',
            ], 403);
        }

        // TODO (6): Check is quiz assigned to course/lesson

        $quiz = $this->quizRepository->getQuiz($quizId);
        if (!$quiz instanceof Quiz) {
            return new JsonResponse([
                'message' => 'Тест не найден',
            ], 404);
        }

        $quizResults = $this->quizRepository->getQuizResults($quizId, $student->id);
        $this->quizHelper->fillQuizWithResults($quiz, $quizResults);

        return new JsonResponse([
            'quiz' => $quiz,
        ]);
    }
}