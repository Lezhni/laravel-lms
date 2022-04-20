<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Modules\Api\Http\Requests\Course\GetRequest;
use Modules\Api\Transformers\Lesson\Resource;
use Modules\Api\Transformers\Lesson\ResourceCollection;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Lesson\Lesson;

/**
 *
 */
class LessonApiController extends Controller
{
    /**
     * @param int $courseId
     * @param \Modules\Api\Http\Requests\Course\GetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(int $courseId, GetRequest $request): JsonResponse
    {
        $courseExists = Course::published()->where('id', $courseId)->exists();
        if (!$courseExists) {
            return new JsonResponse([
                'message' => 'Курс не найден',
            ], 404);
        }

        $lessons = Lesson::whereHas('course', function (Builder $query) use ($courseId) {
            $query->where('id', $courseId);
        })
            ->published()
            ->with('course')
            ->get();

        $resourceCollection = new ResourceCollection($lessons);
        return $resourceCollection->response();
    }

    /**
     * @param int $courseId
     * @param int $id
     * @param \Modules\Api\Http\Requests\Course\GetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(int $courseId, int $id, GetRequest $request): JsonResponse
    {
        $course = Course::published()->where('id', $courseId);
        if (!$course->exists()) {
            return new JsonResponse([
                'message' => 'Курс не найден',
            ], 404);
        }

        $lesson = Lesson::published()->with('course')
            ->where(['course_id' => $courseId, 'id' => $id])
            ->first();
        if (!$lesson instanceof Lesson) {
            return new JsonResponse([
                'message' => 'Занятие не найдено',
            ], 404);
        }

        $resource = new Resource($lesson);
        return $resource->response();
    }
}