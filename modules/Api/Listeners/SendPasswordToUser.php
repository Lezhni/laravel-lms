<?php

namespace Modules\Api\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Api\Events\UserCreated;

class SendPasswordToUser implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param \Modules\Api\Events\UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $user = $event->user;
        $rawPassword = $event->rawPassword;

        $mail = new \Modules\Api\Mail\UserCreated($user, $rawPassword);
        Mail::to($user->email)->send($mail);
    }
}
