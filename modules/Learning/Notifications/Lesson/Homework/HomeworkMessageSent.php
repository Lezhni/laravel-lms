<?php

namespace Modules\Learning\Notifications\Lesson\Homework;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Learning\Helpers\LinksGenerator;
use Modules\Learning\Models\Lesson\Homework\ChatMessage;

/**
 *
 */
class HomeworkMessageSent extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * @param \Modules\Learning\Models\Lesson\Homework\ChatMessage $chatMessage
     */
    public function __construct(protected ChatMessage $chatMessage)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        $this->chatMessage->load('lesson.course');

        $lessonName = $this->chatMessage->lesson->name;

        $message = strip_tags($this->chatMessage->message);
        if (strlen($message) > 50) {
            $message = trim(substr($message, 0, 50)) . '...';
        }

        $courseId = $this->chatMessage->lesson->course->id;
        $lessonId = $this->chatMessage->lesson->id;
        $homeworkLink = LinksGenerator::getHomeworkLink($courseId, $lessonId);

        return [
            'message' => "Новое сообщение к домашнему занятию \"{$lessonName}\": \"{$message}\"",
            'link' => $homeworkLink,
        ];
    }

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType(): string
    {
        return 'student.homework-message-sended';
    }
}