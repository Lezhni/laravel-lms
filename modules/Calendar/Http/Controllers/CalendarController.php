<?php

namespace Modules\Calendar\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Calendar\Helpers\CalendarHelper;
use Modules\Learning\Repositories\CourseRepository;
use Modules\Learning\Services\EnrollmentValidator;

/**
 *
 */
class CalendarController extends Controller
{
    /**
     * @param \Modules\Learning\Repositories\CourseRepository $courseRepository
     * @param \Modules\Learning\Services\EnrollmentValidator $enrollmentValidator
     * @param \Modules\Calendar\Helpers\CalendarHelper $calendarHelper
     */
    public function __construct(
        protected CourseRepository $courseRepository,
        protected EnrollmentValidator $enrollmentValidator,
        protected CalendarHelper $calendarHelper
    )
    {
    }

    /**
     * @param int $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourseEvents(int $courseId): JsonResponse
    {
        $student = Auth::user();

        $courseExists = $this->courseRepository->isCourseExists($courseId);
        if (!$courseExists) {
            return new JsonResponse([
                'message' => 'Курс не найден',
            ], 404);
        }

        $studentEnrolled = $this->enrollmentValidator->isStudentEnrolled($student->id, $courseId);
        if (! $studentEnrolled) {
            return new JsonResponse([
                'message' => 'У вас нет доступа к выбранному курсу',
            ], 403);
        }

        $lessons = $this->courseRepository->getLessons($courseId);

        $events = $this->calendarHelper->convertLessonsToEvents($lessons, $student->id);

        return new JsonResponse([
            'events' => $events,
        ]);
    }
}