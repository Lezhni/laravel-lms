<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

/**
 *
 */
class PasswordReset extends Mailable
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $resetLink = url("password/reset/{$this->token}");
        $siteName = config('mail.from.name');

        return $this
            ->subject("{$siteName} - восстановление пароля")
            ->view('mails.passwords.reset')
            ->with(['resetLink' => $resetLink]);
    }
}
