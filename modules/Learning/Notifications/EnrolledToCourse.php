<?php

namespace Modules\Learning\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Learning\Helpers\LinksGenerator;
use Modules\Learning\Models\Course;

/**
 *
 */
class EnrolledToCourse extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * @param \Modules\Learning\Models\Course $course
     */
    public function __construct(protected Course $course)
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
        $courseLink = LinksGenerator::getCourseLink($this->course->id);

        return [
            'message' => "Вы зачислены на курс \"{$this->course->name}\"",
            'link' => $courseLink,
        ];
    }

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType(): string
    {
        return 'student.enrolled';
    }
}
