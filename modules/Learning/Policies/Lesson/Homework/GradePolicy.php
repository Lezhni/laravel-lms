<?php

namespace Modules\Learning\Policies\Lesson\Homework;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Builder;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Lesson\Homework\Grade;
use Modules\Learning\Models\Lesson\Lesson;

/**
 *
 */
class GradePolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return
            $user->can('homeworks.grade') ||
            Lesson::where('teacher_id', $user->id)->exists() ||
            Course::whereHas('teachers', function (Builder $query) use ($user) {
                $query->where('teacher_id', $user->id);
            })->exists();
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Lesson\Homework\Grade $grade
     * @return bool
     */
    public function view(User $user, Grade $grade): bool
    {
        $grade->load('lesson.course.teachers');

        return
            $user->can('homeworks.grade') ||
            $grade->lesson->course->teachers->contains($user->id) ||
            $grade->lesson->teacher_id === $user->id;
    }

    /**
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Lesson\Homework\Grade $grade
     * @return bool
     */
    public function update(User $user, Grade $grade): bool
    {
        $grade->load('lesson.course.teachers');

        return
            $user->can('homeworks.grade') ||
            $grade->lesson->course->teachers->contains($user->id) ||
            $grade->lesson->teacher_id === $user->id;
    }

    /**
     * @param \App\Models\User $user
     * @param \Modules\Learning\Models\Lesson\Homework\Grade $grade
     * @return bool
     */
    public function delete(User $user, Grade $grade): bool
    {
        return false;
    }
}
