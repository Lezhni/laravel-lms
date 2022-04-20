<?php

namespace CreaceptLms\HomeworkChat\Repositories;

use Modules\Learning\Models\Lesson\Homework\ChatMessage;

/**
 *
 */
class ChatMessageRepository
{
    /**
     * @param int $senderId
     * @param int $gradeId
     * @param string $message
     * @return \Modules\Learning\Models\Lesson\Homework\ChatMessage
     * @throws \Throwable
     */
    public function storeChatMessage(int $senderId, int $gradeId, string $message): ChatMessage
    {
        $chatMessage = new ChatMessage;
        $chatMessage->sender_id = $senderId;
        $chatMessage->grade_id = $gradeId;
        $chatMessage->message = $message;
        $chatMessage->saveOrFail();

        return $chatMessage;
    }
}