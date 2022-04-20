<?php

namespace Modules\Learning\Policies\Lesson\Homework;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Learning\Models\Lesson\Homework\Homework;

/**
 *
 */
class HomeworkPolicy
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
     * @param \Modules\Learning\Models\Lesson\Homework\Homework $homework
     * @return bool
     */
    public function view(User $user, Homework $homework): bool
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
     * @param \Modules\Learning\Models\Lesson\Homework\Homework $homework
     * @return bool
     */
    public function update(User $user, Homework $homework): bool
    {
        return $user->can('courses.update');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Lesson\Homework\Homework $homework
     * @return bool
     */
    public function delete(User $user, Homework $homework): bool
    {
        return $user->can('courses.delete');
    }
}
