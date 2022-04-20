<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Storage;

/**
 *
 */
class UserObserver
{
    /**
     * @param \App\Models\User $user
     */
    public function saving(User $user)
    {
        $oldAvatar = User::where('id', $user->id)->value('avatar');
        if (!$oldAvatar || $oldAvatar === $user->avatar) {
            return;
        }

        Storage::disk('uploads')->delete($oldAvatar);
    }

    /**
     * @param \App\Models\User $user
     */
    public function deleted(User $user)
    {
        Storage::disk('uploads')->delete($user->avatar);

        DatabaseNotification::where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->delete();
    }
}
