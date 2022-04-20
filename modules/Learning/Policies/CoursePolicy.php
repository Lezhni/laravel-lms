<?php

namespace Modules\Learning\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Learning\Models\Course;

/**
 * Class CoursePolicy
 * @package Modules\Learning\Policies
 */
class CoursePolicy
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
     * @param \Modules\Learning\Models\Course $course
     * @return bool
     */
    public function view(User $user, Course $course): bool
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
     * @param \Modules\Learning\Models\Course $course
     * @return bool
     */
    public function update(User $user, Course $course): bool
    {
        return $user->can('courses.update');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Course $course
     * @return bool
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->can('courses.delete');
    }
}
