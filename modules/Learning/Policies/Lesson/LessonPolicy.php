<?php

namespace Modules\Learning\Policies\Lesson;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Learning\Models\Lesson\Lesson;


/**
 *
 */
class LessonPolicy
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
     * @param \Modules\Learning\Models\Lesson\Lesson $lesson
     * @return bool
     */
    public function view(User $user, Lesson $lesson): bool
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
     * @param \Modules\Learning\Models\Lesson\Lesson $lesson
     * @return bool
     */
    public function update(User $user, Lesson $lesson): bool
    {
        return $user->can('courses.update');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Lesson\Lesson $lesson
     * @return bool
     */
    public function delete(User $user, Lesson $lesson): bool
    {
        return $user->can('courses.delete');
    }
}
