<?php

namespace Modules\Learning\Notifications\Nova\Lesson\Homework;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Mirovit\NovaNotifications\Notification as NovaNotification;
use Modules\Learning\Models\Lesson\Homework\ChatMessage;

/**
 *
 */
class HomeworkMessageSent extends Notification
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
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        $this->chatMessage->load(['lesson', 'grade', 'sender']);

        $lessonName = $this->chatMessage->lesson->name;
        $senderName = $this->chatMessage->sender->name;

        $message = strip_tags($this->chatMessage->message);
        if (strlen($message) > 50) {
            $message = trim(substr($message, 0, 50)) . '...';
        }

        return NovaNotification::make()
            ->info("Новое сообщение к домашнему занятию \"{$lessonName}\"")
            ->subtitle("От {$senderName}: \"{$message}\"")
            ->routeEdit('grades', $this->chatMessage->grade->id)
            ->toArray();

    }
}
