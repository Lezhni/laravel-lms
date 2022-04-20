<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Modules\Api\Events\UserCreated;
use Modules\Api\Http\Requests\User\CreateRequest;
use Modules\Api\Http\Requests\User\DeleteRequest;
use Modules\Api\Http\Requests\User\GetEnrollmentsRequest;
use Modules\Api\Http\Requests\User\GetFieldRequest;
use Modules\Api\Http\Requests\User\GetRequest;
use Modules\Api\Http\Requests\User\UpdateRequest;
use Modules\Api\Transformers\Course\ResourceCollection as CourseResourceCollection;
use Modules\Api\Transformers\Enrollment\ResourceCollection as EnrollmentsResourceCollection;
use Modules\Api\Transformers\User\Resource;
use Modules\Api\Transformers\User\ResourceCollection;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Enrollment;
use Modules\Learning\Services\EnrollmentValidator;
use Throwable;

/**
 * Class UserApiController
 * @package Modules\Api\Http\Controllers
 */
class UserApiController extends Controller
{
    /**
     * @param \Modules\Learning\Services\EnrollmentValidator $enrollmentValidator
     */
    public function __construct(protected EnrollmentValidator $enrollmentValidator)
    {
    }

    /**
     * @param \Modules\Api\Http\Requests\User\GetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(GetRequest $request): JsonResponse
    {
        $users = User::notAdmins()->with('roles')->get();

        $resourceCollection = new ResourceCollection($users);
        return $resourceCollection->response();
    }

    /**
     * @param int $id
     * @param \Modules\Api\Http\Requests\User\GetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(int $id, GetRequest $request): JsonResponse
    {
        $user = User::notAdmins()->with('roles')->find($id);
        if (!$user instanceof User) {
            return new JsonResponse([
                'message' => 'Пользователь не найден',
            ], 404);
        }

        $resource = new Resource($user);
        return $resource->response();
    }

    /**
     * @param \Modules\Api\Http\Requests\User\GetFieldRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByField(GetFieldRequest $request): JsonResponse
    {
        $params = $request->only(['field', 'value']);
        $user = User::notAdmins()->with('roles')->where($params['field'], $params['value'])->first();
        if (!$user instanceof User) {
            return new JsonResponse([
                'message' => 'Пользователь не найден',
            ], 404);
        }

        $resource = new Resource($user);
        return $resource->response();
    }

    /**
     * @param \Modules\Api\Http\Requests\User\CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request): JsonResponse
    {
        $params = $request->only(['name', 'email', 'phone', 'password']);
        $rawPassword = $params['password'];
        $params['password'] = Hash::make($rawPassword);

        try {
            $user = new User;
            $user->fill($params);
            $user->saveOrFail();
        } catch (Throwable $e) {
            report($e);
            return new JsonResponse([
                'message' => 'Ошибка сервера, попробуйте еще раз',
            ], 500);
        }

        $roles = $request->post('roles');
        if (is_array($roles) && count($roles) > 0) {
            $user->roles()->attach($roles);
        }

        $userCreated = new UserCreated($user, $rawPassword);
        Event::dispatch($userCreated);

        $resource = new Resource($user);
        return $resource->response()->setStatusCode(201);
    }

    /**
     * @param int $id
     * @param \Modules\Api\Http\Requests\User\UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, UpdateRequest $request): JsonResponse
    {
        $params = $request->only(['name', 'email', 'phone', 'password']);
        if (array_key_exists('password', $params)) {
            $params['password'] = Hash::make($params['password']);
        }

        $user = User::notAdmins()->with('roles')->find($id);
        if (!$user instanceof User) {
            return new JsonResponse([
                'message' => 'Пользователь не найден',
            ], 404);
        }

        try {
            $user->update($params);
            $user->saveOrFail();
        } catch (Throwable $e) {
            report($e);
            return new JsonResponse([
                'message' => 'Ошибка сервера, попробуйте еще раз',
            ], 500);
        }

        $roles = $request->post('roles');
        if (is_array($roles) && count($roles) > 0) {
            $user->roles()->syncWithoutDetaching($roles);
        }

        $resource = new Resource($user);
        return $resource->response();
    }

    /**
     * @param int $id
     * @param \Modules\Api\Http\Requests\User\DeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id, DeleteRequest $request): JsonResponse
    {
        $user = User::notAdmins()->find($id);
        if (!$user instanceof User) {
            return new JsonResponse([
                'message' => 'Пользователь не найден',
            ], 404);
        }

        try {
            $user->delete();
        } catch (Throwable $e) {
            report($e);
            return new JsonResponse([
                'message' => 'Ошибка сервера, попробуйте еще раз',
            ], 500);
        }

        $resource = new Resource($user);
        return $resource->response();
    }

    /**
     * @param int $id
     * @param \Modules\Api\Http\Requests\User\GetEnrollmentsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listEnrollments(int $id, GetEnrollmentsRequest $request): JsonResponse
    {
        $userExists = User::notAdmins()->where('id', $id)->exists();
        if (!$userExists) {
            return new JsonResponse([
                'message' => 'Пользователь не найден',
            ], 404);
        }

        $enrollments = Enrollment::whereHas('student', function (Builder $query) use ($id) {
            $query->where('id', $id);
        })->get();

        $resourceCollection = new EnrollmentsResourceCollection($enrollments);
        return $resourceCollection->response();
    }

    /**
     * @param int $id
     * @param \Modules\Api\Http\Requests\User\GetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listCourses(int $id, GetRequest $request): JsonResponse
    {
        $request->all();
        $userExists = User::notAdmins()->where('id', $id)->exists();
        if (!$userExists) {
            return new JsonResponse([
                'message' => 'Пользователь не найден',
            ], 404);
        }

        $courses = Course::published()
            ->whereHas('students', function ($query) use ($id) {
                $query->where('users.id', $id);
            })
            ->get();

        // TODO: SQL queries in loop isn't good solution. Try to find the best one
        $activeCourses = $courses->filter(function (Course $course) use ($id) {
            return $this->enrollmentValidator->isStudentEnrolled($id, $course->id);
        });

        $resource = new CourseResourceCollection($activeCourses);
        return $resource->response();
    }
}
