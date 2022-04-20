<?php

namespace App\Services;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Repositories\StudentRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

/**
 *
 */
class StudentService
{
    /**
     * @var \App\Repositories\StudentRepository
     */
    protected StudentRepository $studentRepository;

    protected UploadsService $uploadsService;

    /**
     * @param \App\Repositories\StudentRepository $studentRepository
     * @param \App\Services\UploadsService $uploadsService
     */
    public function __construct(StudentRepository $studentRepository, UploadsService $uploadsService)
    {
        $this->studentRepository = $studentRepository;
        $this->uploadsService = $uploadsService;
    }

    /**
     * @param User $student
     * @param \App\Http\Requests\ProfileRequest $request
     * @return User
     */
    public function updateProfile(User $student, ProfileRequest $request): User
    {
        $data = $request->validated();
        $files = $request->allFiles();

        $avatar = Arr::get($files, 'avatar');
        if ($avatar instanceof UploadedFile) {
            $avatarFilename = $this->uploadsService->storeUpload($avatar, User::IMAGES_FOLDER, true);
            $data['avatar'] = User::IMAGES_FOLDER . '/' . $avatarFilename;
        }

        return $this->studentRepository->updateProfile($student, $data);
    }
}