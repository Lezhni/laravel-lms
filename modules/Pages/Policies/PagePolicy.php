<?php

namespace Modules\Pages\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Pages\Models\Page;

/**
 *
 */
class PagePolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('pages.list');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Pages\Models\Page $page
     * @return bool
     */
    public function view(User $user, Page $page): bool
    {
        return $user->can('pages.list');
    }

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('pages.create');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Pages\Models\Page $page
     * @return bool
     */
    public function update(User $user, Page $page): bool
    {
        return $user->can('pages.update');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Pages\Models\Page $page
     * @return bool
     */
    public function delete(User $user, Page $page): bool
    {
        return $user->can('pages.delete');
    }
}
