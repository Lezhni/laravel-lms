<?php

namespace Modules\Learning\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Learning\Models\Attachment\Attachment;

/**
 *
 */
class AttachmentPolicy
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
     * @param \Modules\Learning\Models\Attachment\Attachment $attachment
     * @return bool
     */
    public function view(User $user, Attachment $attachment): bool
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
     * @param \Modules\Learning\Models\Attachment\Attachment $attachment
     * @return bool
     */
    public function update(User $user, Attachment $attachment): bool
    {
        return $user->can('courses.update');
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Attachment\Attachment $attachment
     * @return bool
     */
    public function delete(User $user, Attachment $attachment): bool
    {
        return $user->can('courses.delete');
    }
}
