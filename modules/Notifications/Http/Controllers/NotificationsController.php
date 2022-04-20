<?php

namespace Modules\Notifications\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Notifications\Helpers\NotificationsHelper;

/**
 *
 */
class NotificationsController extends Controller
{
    /**
     * @param \Modules\Notifications\Helpers\NotificationsHelper $helper
     */
    public function __construct(protected NotificationsHelper $helper)
    {
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(): JsonResponse
    {
        $student = Auth::user();

        $twoWeeksAgo = Carbon::now()->subWeeks(2);

        $newNotifications = $student->notifications()->where('created_at', '>=', $twoWeeksAgo)->get();
        $newNotifications = $this->helper->formatNotifications($newNotifications);

        return new JsonResponse([
            'notifications' => $newNotifications,
        ]);
    }

    /**
     * @param string|null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function readNotifications(string $id = null): JsonResponse
    {
        $student = Auth::user();

        $notifications = DatabaseNotification::query()
            ->where('notifiable_type', $student::class)
            ->where('notifiable_id', $student->id);
        if ($id !== null) {
            $notifications = $notifications->where('id', $id);
        }

        $notifications->update(['read_at' => Carbon::now()]);

        return new JsonResponse([
            'message' => 'Уведомление прочтено',
        ]);
    }
}
