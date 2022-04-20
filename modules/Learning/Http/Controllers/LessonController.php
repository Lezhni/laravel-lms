<?php

namespace Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Learning\Helpers\CourseHelper;
use Modules\Learning\Models\Lesson\Lesson;
use Modules\Learning\Repositories\CourseRepository;
use Modules\Learning\Services\EnrollmentValidator;
use Modules\Learning\Services\LessonService;

/**
 *
 */
class LessonController extends Controller
{
    /**
     * @param \Modules\Learning\Services\LessonService $lessonService
     * @param \Modules\Learning\Helpers\CourseHelper $coursesHelper
     * @param \Modules\Learning\Repositories\CourseRepository $courseRepository
     * @param \Modules\Learning\Services\EnrollmentValidator $enrollmentValidator
     */
    public function __construct(
        protected LessonService $lessonService,
        protected CourseHelper $coursesHelper,
        protected CourseRepository $courseRepository,
        protected EnrollmentValidator $enrollmentValidator
    )
    {
    }

    /**
     * @param int $courseId
     * @param int $lessonId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLesson(int $courseId, int $lessonId): JsonResponse
    {
        $student = Auth::user();

        $studentEnrolled = $this->enrollmentValidator->isStudentEnrolled($student->id, $courseId);
        if (!$studentEnrolled) {
            return new JsonResponse([
                'message' => 'У вас нет доступа к выбранному курсу',
            ], 403);
        }

        // TODO (2): Check is lesson assigned to course

        $lesson = $this->courseRepository->getLesson($lessonId);
        if (!$lesson instanceof Lesson) {
            return new JsonResponse([
                'message' => 'Занятие не найдено',
            ], 404);
        }

        $lesson = $this->coursesHelper->formatLesson($lesson, $student->id);

        $isOpened = $this->lessonService->isOpened($lesson);
        if (!$isOpened) {
            return new JsonResponse([
                'message' => 'Занятие ещё не началось',
            ], 403);
        }

        return new JsonResponse([
            'lesson' => $lesson,
        ]);
    }
}