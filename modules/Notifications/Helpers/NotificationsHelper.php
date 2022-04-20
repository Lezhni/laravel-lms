<?php

namespace Modules\Notifications\Helpers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;

/**
 *
 */
class NotificationsHelper
{
    /**
     * @param \Illuminate\Support\Collection $notifications
     * @return \Illuminate\Support\Collection
     */
    public function formatNotifications(Collection $notifications): Collection
    {
        return $notifications->map(function (DatabaseNotification $notification) {
            return [
                'id' => $notification->id,
                'data' => $notification->data,
                'created_at' => $notification->created_at,
                'read' => ($notification->read_at !== null),
            ];
        });
    }
}