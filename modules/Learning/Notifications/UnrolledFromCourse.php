<?php

namespace Modules\Learning\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Learning\Models\Course;

/**
 *
 */
class UnrolledFromCourse extends Notification implements ShouldQueue, ShouldBroadcast
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
        return [
            'message' => "Вы отчислены с курса \"{$this->course->name}\""
        ];
    }

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType(): string
    {
        return 'student.unrolled';
    }
}
