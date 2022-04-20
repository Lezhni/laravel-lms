<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class ProfileController extends Controller
{
    /**
     * @var \App\Services\StudentService
     */
    protected StudentService $studentService;

    /**
     * @param \App\Services\StudentService $studentService
     */
    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        $student = Auth::user();

        return new JsonResponse([
            'student' => $student,
        ]);
    }

    /**
     * @param \App\Http\Requests\ProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(ProfileRequest $request): JsonResponse
    {
        $student = Auth::user();

        $updatedProfile = $this->studentService->updateProfile($student, $request);
        if (!$updatedProfile) {
            return new JsonResponse([
                'message' => 'Не удалось обновить профиль, попробуйте еще раз',
            ], 500);
        }

        return new JsonResponse([
            'student' => $updatedProfile,
        ]);
    }
}