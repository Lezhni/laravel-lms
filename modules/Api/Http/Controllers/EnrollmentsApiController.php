<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Api\Http\Requests\Enrollment\CreateRequest;
use Modules\Api\Http\Requests\Enrollment\DeleteRequest;
use Modules\Api\Http\Requests\Enrollment\GetRequest;
use Modules\Api\Http\Requests\Enrollment\UpdateRequest;
use Modules\Api\Transformers\Enrollment\Resource;
use Modules\Api\Transformers\Enrollment\ResourceCollection;
use Modules\Learning\Models\Enrollment;
use Throwable;

/**
 *
 */
class EnrollmentsApiController extends Controller
{
    /**
     * @param \Modules\Api\Http\Requests\Enrollment\GetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(GetRequest $request): JsonResponse
    {
        $enrollments = Enrollment::get();

        $resourceCollection = new ResourceCollection($enrollments);
        return $resourceCollection->response();
    }

    /**
     * @param int $id
     * @param \Modules\Api\Http\Requests\Enrollment\GetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(int $id, GetRequest $request): JsonResponse
    {
        $enrollment = Enrollment::find($id);
        if (!$enrollment instanceof Enrollment) {
            return new JsonResponse([
                'message' => 'Зачисление не найдено',
            ], 404);
        }

        $resource = new Resource($enrollment);
        return $resource->response();
    }

    /**
     * @param \Modules\Api\Http\Requests\Enrollment\CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request): JsonResponse
    {
        $params = $request->validated();

        $enrollmentExists = Enrollment::where('course_id', $params['course_id'])
            ->where('student_id', $params['student_id'])
            ->exists();
        if ($enrollmentExists) {
            return new JsonResponse([
                'message' => 'Пользователь уже зачислен на курс',
            ], 409);
        }

        try {
            $enrollment = new Enrollment;
            $enrollment->fill($params);
            $enrollment->saveOrFail();
        } catch (Throwable $e) {
            report($e);
            return new JsonResponse([
                'message' => 'Ошибка сервера, попробуйте еще раз',
            ], 500);
        }

        $resource = new Resource($enrollment);
        return $resource->response()->setStatusCode(201);
    }

    /**
     * @param int $id
     * @param \Modules\Api\Http\Requests\Enrollment\UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, UpdateRequest $request): JsonResponse
    {
        $params = $request->validated();

        $enrollment = Enrollment::find($id);
        if (!$enrollment instanceof Enrollment) {
            return new JsonResponse([
                'message' => 'Зачисление не найдено',
            ], 404);
        }

        try {
            $enrollment->update($params);
            $enrollment->saveOrFail();
        } catch (Throwable $e) {
            report($e);
            return new JsonResponse([
                'message' => 'Ошибка сервера, попробуйте еще раз',
            ], 500);
        }

        $resource = new Resource($enrollment);
        return $resource->response();
    }

    /**
     * @param int $id
     * @param \Modules\Api\Http\Requests\Enrollment\DeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id, DeleteRequest $request): JsonResponse
    {
        $enrollment = Enrollment::find($id);
        if (!$enrollment instanceof Enrollment) {
            return new JsonResponse([
                'message' => 'Зачисление не найдено',
            ], 404);
        }

        try {
            $enrollment->delete();
        } catch (Throwable $e) {
            report($e);
            return new JsonResponse([
                'message' => 'Ошибка сервера, попробуйте еще раз',
            ], 500);
        }

        $resource = new Resource($enrollment);
        return $resource->response();
    }
}