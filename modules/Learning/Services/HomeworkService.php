<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\Lesson\Homework\Grade;
use Modules\Learning\Repositories\HomeworkRepository;

/**
 *
 */
class HomeworkService
{
    /**
     * @param \Modules\Learning\Repositories\HomeworkRepository $homeworkRepository
     */
    public function __construct(protected HomeworkRepository $homeworkRepository)
    {
    }

    /**
     * @param int $senderId
     * @param int $lessonId
     * @param string|null $message
     * @param array $attachments
     * @throws \Throwable
     */
    public function processChatMessage(int $senderId, int $lessonId, ?string $message, array $attachments = [])
    {
        $homework = $this->homeworkRepository->getHomework($lessonId);
        $homeworkGrade = $this->homeworkRepository->getGrade($senderId, $homework->id);
        if (! $homeworkGrade instanceof Grade) {
            $homeworkGrade = $this->homeworkRepository->createGrade($senderId, $homework->id);
        }

        $this->homeworkRepository->storeChatMessage($senderId, $homeworkGrade->id, $message, $attachments);
    }
}