<?php

namespace Modules\Api\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

/**
 *
 */
class UserCreated extends Mailable
{
    /**
     * @var \App\Models\User
     */
    public User $user;

    /**
     * @var string
     */
    public string $rawPassword;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $user
     * @param string $rawPassword
     */
    public function __construct(User $user, string $rawPassword)
    {
        $this->user = $user;
        $this->rawPassword = $rawPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $siteName = config('mail.from.name');

        return $this
            ->subject("{$siteName} - регистрация на платформе")
            ->view('api::mails.user-created')
            ->with(['siteName' => $siteName]);
    }
}
