<?php

namespace App\Policies\ACL;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;

/**
 * Class PermissionPolicy
 * @package App\Policies\ACL
 */
class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('roles.list');
    }

    /**
     * @param \App\Models\User $user
     * @param \Spatie\Permission\Models\Permission $permission
     * @return bool
     */
    public function view(User $user, Permission $permission): bool
    {
        return false;
    }

    /**
     * @param \App\Models\User $user
     * @param \Spatie\Permission\Models\Permission $permission
     * @return bool
     */
    public function update(User $user, Permission $permission): bool
    {
        return false;
    }

    /**
     * @param \App\Models\User $user
     * @param \Spatie\Permission\Models\Permission $permission
     * @return bool
     */
    public function delete(User $user, Permission $permission): bool
    {
        return false;
    }
}
