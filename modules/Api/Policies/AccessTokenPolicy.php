<?php

namespace Modules\Api\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Api\Models\AccessToken;

/**
 * Class AccessTokenPolicy
 * @package Modules\Api\Policies
 */
class AccessTokenPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('api.list');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Api\Models\AccessToken $accessToken
     * @return bool
     */
    public function view(User $user, AccessToken $accessToken): bool
    {
        return $user->can('api.list');
    }

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('api.create');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Api\Models\AccessToken $accessToken
     * @return bool
     */
    public function update(User $user, AccessToken $accessToken): bool
    {
        return $user->can('api.update');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Api\Models\AccessToken $accessToken
     * @return bool
     */
    public function delete(User $user, AccessToken $accessToken): bool
    {
        return $user->can('api.delete');
    }
}
