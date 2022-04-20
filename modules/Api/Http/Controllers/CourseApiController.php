<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Modules\Api\Http\Requests\Course\GetRequest;
use Modules\Api\Transformers\Course\Resource;
use Modules\Api\Transformers\Course\ResourceCollection;
use Modules\Api\Transformers\User\ResourceCollection as UserResourceCollection;
use Modules\Learning\Models\Course;
use Modules\Learning\Services\EnrollmentValidator;

/**
 *
 */
class CourseApiController extends Controller
{
    /**
     * @param \Modules\Learning\Services\EnrollmentValidator $enrollmentValidator
     */
    public function __construct(protected EnrollmentValidator $enrollmentValidator)
    {
    }

    /**
     * @param \Modules\Api\Http\Requests\Course\GetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(GetRequest $request): JsonResponse
    {
        $courses = Course::published()->get();

        $resourceCollection = new ResourceCollection($courses);
        return $resourceCollection->response();
    }

    /**
     * @param int $id
     * @param \Modules\Api\Http\Requests\Course\GetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(int $id, GetRequest $request): JsonResponse
    {
        $course = Course::published()->find($id);
        if (!$course instanceof Course) {
            return new JsonResponse([
                'message' => 'Курс не найден',
            ], 404);
        }

        $resource = new Resource($course);
        return $resource->response();
    }

    /**
     * @param int $id
     * @param \Modules\Api\Http\Requests\Course\GetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listStudents(int $id, GetRequest $request): JsonResponse
    {
        $course = Course::published()
            ->with('students', function ($query) { $query->notAdmins(); })
            ->select(['id'])
            ->find($id);

        if (!$course instanceof Course) {
            return new JsonResponse([
                'message' => 'Курс не найден',
            ], 404);
        }

        // TODO: SQL queries in loop isn't good solution. Try to find the best one
        $enrolledStudents = $course->students->filter(function (User $student) {
            return $this->enrollmentValidator->isStudentEnrolled($student->id, $student->pivot->course_id);
        });

        $resource = new UserResourceCollection($enrolledStudents);
        return $resource->response();
    }
}