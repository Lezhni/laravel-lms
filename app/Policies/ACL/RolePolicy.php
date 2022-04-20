<?php

namespace App\Policies\ACL;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

/**
 * Class RolePolicy
 * @package App\Policies\ACL
 */
class RolePolicy
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
     * @param \Spatie\Permission\Models\Role $role
     * @return bool
     */
    public function view(User $user, Role $role): bool
    {
        return $user->can('roles.list');
    }

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('roles.create');
    }

    /**
     * @param \App\Models\User $user
     * @param \Spatie\Permission\Models\Role $role
     * @return bool
     */
    public function update(User $user, Role $role): bool
    {
        return $user->can('roles.update');
    }

    /**
     * @param \App\Models\User $user
     * @param \Spatie\Permission\Models\Role $role
     * @return bool
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->can('roles.delete');
    }
}
