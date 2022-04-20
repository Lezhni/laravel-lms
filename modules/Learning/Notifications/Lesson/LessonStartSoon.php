<?php

namespace Modules\Learning\Notifications\Lesson;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Learning\Helpers\LinksGenerator;
use Modules\Learning\Models\Lesson\Lesson;

/**
 *
 */
class LessonStartSoon extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * @param \Modules\Learning\Models\Lesson\Lesson $lesson
     */
    public function __construct(protected Lesson $lesson)
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
        $this->lesson->load('course');

        $lessonLink = LinksGenerator::getLessonLink($this->lesson->course->id, $this->lesson->id);

        return [
            'message' => "Занятие \"{$this->lesson->name}\" скоро начнется",
            'link' => $lessonLink,
        ];
    }

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType(): string
    {
        return 'lesson.start-soon';
    }
}