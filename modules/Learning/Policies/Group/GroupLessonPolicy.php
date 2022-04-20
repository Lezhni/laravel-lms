<?php

namespace Modules\Learning\Policies\Group;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Learning\Models\Group\GroupLesson;

/**
 * Class GroupLessonPolicy
 * @package Modules\Learning\Policies\Group
 */
class GroupLessonPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('courses.list');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Group\GroupLesson $groupLesson
     * @return bool
     */
    public function view(User $user, GroupLesson $groupLesson): bool
    {
        return $user->can('courses.list');
    }

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('courses.create');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Group\GroupLesson $groupLesson
     * @return bool
     */
    public function update(User $user, GroupLesson $groupLesson): bool
    {
        return $user->can('courses.update');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Group\GroupLesson $groupLesson
     * @return bool
     */
    public function delete(User $user, GroupLesson $groupLesson): bool
    {
        return $user->can('courses.delete');
    }
}
