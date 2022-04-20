<?php

namespace Modules\Learning\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Learning\Models\Enrollment;

/**
 * Class EnrollmentPolicy
 * @package Modules\Learning\Policies
 */
class EnrollmentPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('enrollments.list');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Enrollment $enrollment
     * @return bool
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        return $user->can('enrollments.list');
    }

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('enrollments.create');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Enrollment $enrollment
     * @return bool
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        return $user->can('enrollments.update');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Enrollment $enrollment
     * @return bool
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->can('enrollments.delete');
    }
}
