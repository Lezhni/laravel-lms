<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

/**
 *
 */
class PasswordResetConfirmed extends Mailable implements ShouldQueue
{
    /**
     * @var string
     */
    public string $password;

    /**
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->password = $password;
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
            ->subject("{$siteName} - пароль успешно изменен")
            ->view('mails.passwords.confirmation');
    }
}
