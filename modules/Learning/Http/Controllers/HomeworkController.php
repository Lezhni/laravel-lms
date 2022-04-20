<?php

namespace Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Learning\Helpers\HomeworkHelper;
use Modules\Learning\Http\Requests\Lesson\Homework\ChatMessageRequest;
use Modules\Learning\Models\Lesson\Homework\Homework;
use Modules\Learning\Repositories\HomeworkRepository;
use Modules\Learning\Services\EnrollmentValidator;
use Modules\Learning\Services\HomeworkService;
use Throwable;

/**
 *
 */
class HomeworkController extends Controller
{
    /**
     * @param \Modules\Learning\Repositories\HomeworkRepository $homeworkRepository
     * @param \Modules\Learning\Services\EnrollmentValidator $enrollmentValidator
     * @param \Modules\Learning\Services\HomeworkService $homeworkService
     * @param \Modules\Learning\Helpers\HomeworkHelper $homeworkHelper
     */
    public function __construct(
        protected HomeworkRepository $homeworkRepository,
        protected EnrollmentValidator $enrollmentValidator,
        protected HomeworkService $homeworkService,
        protected HomeworkHelper $homeworkHelper
    )
    {
    }

    /**
     * @param int $courseId
     * @param int $lessonId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHomework(int $courseId, int $lessonId): JsonResponse
    {
        $student = Auth::user();

        $studentEnrolled = $this->enrollmentValidator->isStudentEnrolled($student->id, $courseId);
        if (! $studentEnrolled) {
            return new JsonResponse([
                'message' => 'У вас нет доступа к выбранному курсу',
            ], 403);
        }

        // TODO (2): Check is lesson assigned to course

        $homework = $this->homeworkRepository->getHomework($lessonId, $student->id);
        if (!$homework instanceof Homework) {
            return new JsonResponse([
                'message' => 'Домашнее задание не найдено',
            ], 404);
        }

        $homework = $this->homeworkHelper->prepareHomework($homework);

        return new JsonResponse([
            'homework' => $homework,
        ]);
    }

    /**
     * @param int $courseId
     * @param int $lessonId
     * @param \Modules\Learning\Http\Requests\Lesson\Homework\ChatMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendChatMessage(int $courseId, int $lessonId, ChatMessageRequest $request): JsonResponse
    {
        $student = Auth::user();

        $studentEnrolled = $this->enrollmentValidator->isStudentEnrolled($student->id, $courseId);
        if (! $studentEnrolled) {
            return new JsonResponse([
                'message' => 'У вас нет доступа к выбранному курсу',
            ], 403);
        }

        // TODO (4): Check is lesson assigned to course

        $homeworkExists = $this->homeworkRepository->isHomeworkExists($lessonId);
        if (! $homeworkExists) {
            return new JsonResponse([
                'message' => 'Домашнее задание не найдено',
            ], 404);
        }

        try {
            $data = $request->validated();
            $message = Arr::get($data, 'message');
            $attachments = Arr::get($data, 'attachments', []);
            $this->homeworkService->processChatMessage($student->id, $lessonId, $message, $attachments);
        } catch (Throwable $e) {
            report($e);
            return new JsonResponse([
                'message' => 'Не удалось отправить сообщение, попробуйте еще раз',
            ], 500);
        }

        return new JsonResponse([
            'message' => 'Сообщение отправлено',
        ], 201);
    }
}