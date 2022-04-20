<?php

namespace Modules\Calendar\Services\Group;

use Illuminate\Database\Eloquent\Collection;
use Modules\Learning\Models\Enrollment;
use Modules\Learning\Models\Group\Group;
use Modules\Learning\Models\Group\GroupLesson;
use Modules\Learning\Models\Lesson\Lesson;

/**
 * Class DataReplacer
 * @package Modules\Calendar\Services\Group
 */
class DataReplacer
{
    /**
     * @param \Illuminate\Database\Eloquent\Collection $lessons
     * @param int $studentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function replaceDatesForGroup(Collection $lessons, int $studentId): Collection
    {
        $studentGroup = Enrollment::with('group.overriddenLessons')
            ->where('course_id', $lessons->first()->course_id)
            ->where('student_id', $studentId)
            ->first()
            ->group;
        if (!$studentGroup instanceof Group) {
            return $lessons;
        }

        return $lessons->map(function (Lesson $lesson) use ($studentGroup) {
            $lessonForGroup = $studentGroup->overriddenLessons->where('lesson_id', $lesson->id)->first();
            if (!$lessonForGroup instanceof GroupLesson) {
                return $lesson;
            }

            $lesson->started_at = $lessonForGroup->started_at;
            return $lesson;
        });
    }
}