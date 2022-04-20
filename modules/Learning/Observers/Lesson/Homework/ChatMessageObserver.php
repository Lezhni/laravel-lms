<?php

namespace Modules\Learning\Observers\Lesson\Homework;

use Illuminate\Support\Facades\Notification;
use Modules\Learning\Models\Lesson\Homework\ChatMessage;
use Modules\Learning\Notifications\Lesson\Homework\HomeworkMessageSent;
use Modules\Learning\Notifications\Nova\Lesson\Homework\HomeworkMessageSent as NovaHomeworkMessageSent;

/**
 *
 */
class ChatMessageObserver
{
    /**
     * @param \Modules\Learning\Models\Lesson\Homework\ChatMessage $chatMessage
     */
    public function created(ChatMessage $chatMessage)
    {
        $chatMessage->load('sender');

        ($chatMessage->sender->is_admin)
            ? $this->notifyStudent($chatMessage)
            : $this->notifyTeachers($chatMessage);
    }

    /**
     * @param \Modules\Learning\Models\Lesson\Homework\ChatMessage $chatMessage
     * @return void
     */
    protected function notifyStudent(ChatMessage $chatMessage)
    {
        $chatMessage->load('grade.student');
        $chatMessage->grade->student->notify(new HomeworkMessageSent($chatMessage));
    }

    /**
     * @param \Modules\Learning\Models\Lesson\Homework\ChatMessage $chatMessage
     * @return void
     */
    protected function notifyTeachers(ChatMessage $chatMessage)
    {
        $chatMessage->load('lesson.course.teachers');
        $teachers = $chatMessage->lesson->course->teachers;
        Notification::send($teachers, new NovaHomeworkMessageSent($chatMessage));
    }
}