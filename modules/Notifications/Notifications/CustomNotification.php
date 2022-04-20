<?php

namespace Modules\Notifications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Notifications\Helpers\DTO\Notification as NotificationDTO;

/**
 *
 */
class CustomNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * @var \Modules\Notifications\Helpers\DTO\Notification
     */
    protected NotificationDTO $notification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(NotificationDTO $notification)
    {
        $this->notification = $notification;
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
            'message' => $this->notification->getText(),
            'link' => $this->notification->getLink(),
        ];
    }

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType(): string
    {
        return 'student.custom-notification';
    }
}
