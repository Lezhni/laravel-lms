<?php

namespace Modules\Learning\Services\Group;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Learning\Models\Attachment\Attachment;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Enrollment;
use Modules\Learning\Models\Group\Group;
use Modules\Learning\Models\Group\GroupLesson;
use Modules\Learning\Models\Lesson\Lesson;

/**
 * Class GroupDataReplacer
 * @package Modules\Learning\Services\Group
 */
class DataReplacer
{
    /**
     * @param \Modules\Learning\Models\Course $course
     * @return \Modules\Learning\Models\Course
     */
    public function replaceCourseDataForGroup(Course $course): Course
    {
        $studentGroup = $course->enrollments->first()->group;
        if (!$studentGroup instanceof Group) {
            return $course;
        }

        $course->started_at = $studentGroup->started_at;
        $course->finished_at = $studentGroup->finished_at;
        $course->access_closed_at = $studentGroup->access_closed_at;

        $groupCourseContent = $studentGroup->course_content ?? [];
        $courseContent = $course->content;
        $course->content = array_merge($groupCourseContent, $courseContent);

        $course->lessons->map(function (Lesson $lesson) use ($studentGroup) {
            $lessonForGroup = $studentGroup->overriddenLessons->where('lesson_id', $lesson->id)->first();
            if (!$lessonForGroup instanceof GroupLesson) {
                return $lesson;
            }

            $lesson->started_at = $lessonForGroup->started_at;
            return $lesson;
        });

        return $course;
    }

    /**
     * @param \Modules\Learning\Models\Course $course
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCourseAttachments(Course $course): Collection
    {
        $studentGroup = $course->enrollments->first()->group;

        $attachments = Attachment::with('category')->ordered();
        $attachments = ($studentGroup instanceof Group)
            ? $attachments->whereHas('groups', function (Builder $query) use ($studentGroup) {
                $query->where('id', $studentGroup->id);
            })
            : $attachments->where('course_id', $course->id);

        return $attachments->get();
    }

    /**
     * @param \Modules\Learning\Models\Lesson\Lesson $lesson
     * @param int $studentId
     * @return \Modules\Learning\Models\Lesson\Lesson
     */
    public function replaceLessonDataForGroup(Lesson $lesson, int $studentId): Lesson
    {
        $studentGroup = Enrollment::with('group.overriddenLessons')
            ->where('course_id', $lesson->course->id)
            ->where('student_id', $studentId)
            ->first()
            ->group;
        if (!$studentGroup instanceof Group) {
            return $lesson;
        }

        $lessonForGroup = $studentGroup->overriddenLessons->where('lesson_id', $lesson->id)->first();
        if (!$lessonForGroup instanceof GroupLesson) {
            return $lesson;
        }

        $lesson->started_at = $lessonForGroup->started_at;
        $lesson->record_link = $lessonForGroup->record_link;

        // TODO: Refactor this, make `lesson_link` as standalone model's property
        $content = $lesson->content;
        if (count($content) == 0) {
            $content[] = [
                'layout' => 'block-zoom-room',
                'key' => 'lessonlink',
                'attributes' => [ 'link' => $lessonForGroup->lesson_link ],
            ];
            $lesson->content = $content;
            return $lesson;
        }

        foreach ($content as &$block) {
            if ($block['layout'] === 'block-zoom-room') {
                $block['attributes']['link'] = $lessonForGroup->lesson_link;
                break;
            }
        }
        $lesson->content = $content;

        return $lesson;
    }
}