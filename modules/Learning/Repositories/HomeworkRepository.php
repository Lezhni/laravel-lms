<?php

namespace Modules\Learning\Repositories;

use App\Services\UploadsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Modules\Learning\Models\Lesson\Homework\ChatAttachment;
use Modules\Learning\Models\Lesson\Homework\ChatMessage;
use Modules\Learning\Models\Lesson\Homework\Grade;
use Modules\Learning\Models\Lesson\Homework\Homework;

/**
 *
 */
class HomeworkRepository
{
    /**
     * @param \App\Services\UploadsService $uploadsService
     */
    public function __construct(protected UploadsService $uploadsService)
    {
    }

    /**
     * @param int $lessonId
     * @return bool
     */
    public function isHomeworkExists(int $lessonId): bool
    {
        return Homework::published()
            ->where('lesson_id', $lessonId)
            ->exists();
    }

    /**
     * @param int $lessonId
     * @param int|null $studentId
     * @return \Modules\Learning\Models\Lesson\Lesson|null
     */
    public function getHomework(int $lessonId, int $studentId = null): ?Homework
    {
        $homework = Homework::published()
            ->select(['id', 'lesson_id', 'content', 'max_grade'])
            ->with(['lesson' => function ($query) { $query->select(['id', 'name']); }])
            ->where('lesson_id', $lessonId)
            ->first();

        if ($studentId == null) {
            return $homework;
        }

        $grade = Grade::with(['messages.sender', 'messages.attachments'])
            ->where('student_id', $studentId)
            ->where('homework_id', $homework->id)
            ->first();
        $homework->messages = ($grade instanceof Grade)
            ? $grade->messages
            : [];

        return $homework;
    }

    /**
     * @param int $studentId
     * @param int $homeworkId
     * @return bool
     */
    public function isGradeExists(int $studentId, int $homeworkId): bool
    {
        return Grade::query()
            ->where('homework_id', $homeworkId)
            ->where('student_id', $studentId)
            ->exists();
    }

    /**
     * @param int $studentId
     * @param int $homework_id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|null
     */
    public function getGrade(int $studentId, int $homework_id): Model|Builder|null
    {
        return Grade::with(['student', 'homework'])
            ->where('homework_id', $homework_id)
            ->where('student_id', $studentId)
            ->first();
    }

    /**
     * @param int $studentId
     * @param int $homeworkId
     * @return \Modules\Learning\Models\Lesson\Homework\Grade
     * @throws \Throwable
     */
    public function createGrade(int $studentId, int $homeworkId): Grade
    {
        $grade = new Grade;
        $grade->student_id = $studentId;
        $grade->homework_id = $homeworkId;
        $grade->saveOrFail();

        $grade->load(['student', 'homework']);
        return $grade;
    }

    /**
     * @param int $senderId
     * @param int $gradeId
     * @param string|null $message
     * @param array $attachments
     * @return \Modules\Learning\Models\Lesson\Homework\ChatMessage
     * @throws \Throwable
     */
    public function storeChatMessage(int $senderId, int $gradeId, ?string $message, array $attachments): ChatMessage
    {
        $chatMessage = new ChatMessage;
        $chatMessage->message = $message;
        $chatMessage->grade_id = $gradeId;
        $chatMessage->sender_id = $senderId;

        DB::transaction(function () use ($chatMessage, $attachments) {
            $chatMessage->saveOrFail();
            foreach ($attachments as $file) {
                $this->storeChatAttachment($file, $chatMessage->id);
            }
        });

        return $chatMessage;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param int $messageId
     * @return \Modules\Learning\Models\Lesson\Homework\ChatAttachment
     * @throws \Throwable
     */
    protected function storeChatAttachment(UploadedFile $file, int $messageId): ChatAttachment
    {
        $filename = $this->uploadsService->storeUpload($file, ChatAttachment::UPLOADS_FOLDER);

        $chatAttachment = new ChatAttachment;
        $chatAttachment->message_id = $messageId;
        $chatAttachment->filename = $filename;
        $chatAttachment->saveOrFail();

        return $chatAttachment;
    }
}