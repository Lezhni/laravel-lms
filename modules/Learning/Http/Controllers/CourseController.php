<?php

namespace Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Learning\Helpers\CourseHelper;
use Modules\Learning\Models\Course;
use Modules\Learning\Repositories\CourseRepository;
use Modules\Learning\Services\EnrollmentValidator;

/**
 *
 */
class CourseController extends Controller
{
    /**
     * @param \Modules\Learning\Helpers\CourseHelper $coursesHelper
     * @param \Modules\Learning\Repositories\CourseRepository $coursesRepository
     * @param \Modules\Learning\Services\EnrollmentValidator $enrollmentValidator
     */
    public function __construct(
        protected CourseHelper $coursesHelper,
        protected CourseRepository $coursesRepository,
        protected EnrollmentValidator $enrollmentValidator
    )
    {
    }

    /**
     * @param int $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourse(int $courseId): JsonResponse
    {
        $student = Auth::user();

        $course = $this->coursesRepository->getStudentCourse($courseId, $student->id);
        if (!$course instanceof Course) {
            return new JsonResponse([
                'message' => 'Курс не найден',
            ], 404);
        }

        $studentEnrolled = $this->enrollmentValidator->isStudentEnrolled($student->id, $course->id);
        if (! $studentEnrolled) {
            return new JsonResponse([
                'message' => 'У вас нет доступа к выбранному курсу',
            ], 403);
        }

        $course = $this->coursesHelper->formatCourse($course);

        return new JsonResponse([
            'course' => $course,
        ]);
    }
}