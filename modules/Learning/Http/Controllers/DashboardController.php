<?php

namespace Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Learning\Helpers\CourseHelper;
use Modules\Learning\Repositories\CourseRepository;

class DashboardController extends Controller
{
    /**
     * @param \Modules\Learning\Helpers\CourseHelper $coursesHelper
     * @param \Modules\Learning\Repositories\CourseRepository $coursesRepository
     */
    public function __construct(
        protected CourseHelper $coursesHelper,
        protected CourseRepository $coursesRepository
    )
    {
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentDashboard(): JsonResponse
    {
        $student = Auth::user();

        $courses = $this->coursesRepository->getStudentCourses($student->id);

        list($activeCourses, $pastCourses) = $this->coursesHelper->formatCoursesForDashboard($courses);

        return new JsonResponse([
            'activeCourses' => $activeCourses,
            'pastCourses' => $pastCourses,
        ]);
    }
}