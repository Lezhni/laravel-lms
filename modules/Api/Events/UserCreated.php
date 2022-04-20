<?php

namespace Modules\Api\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 *
 */
class UserCreated
{
    use Dispatchable, SerializesModels;

    /**
     * @var \App\Models\User
     */
    public User $user;

    /**
     * @var string
     */
    public string $rawPassword;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\User $user
     * @param string $rawPassword
     */
    public function __construct(User $user, string $rawPassword)
    {
        $this->user = $user;
        $this->rawPassword = $rawPassword;
    }
}
