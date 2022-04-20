<?php

namespace Modules\Learning\Helpers;

use App\Helpers\BlockEditor\Helper;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Storage;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Lesson\Lesson;
use Modules\Learning\Services\EnrollmentValidator;
use Modules\Learning\Services\Group\DataReplacer;

/**
 *
 */
class CourseHelper
{
    /**
     * @param \App\Helpers\BlockEditor\Helper $contentHelper
     * @param \Modules\Learning\Services\EnrollmentValidator $enrollmentValidator
     * @param \Modules\Learning\Services\Group\DataReplacer $dataReplacer
     */
    public function __construct(
        protected Helper $contentHelper,
        protected EnrollmentValidator $enrollmentValidator,
        protected DataReplacer $dataReplacer
    )
    {
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $courses
     * @return SupportCollection
     */
    public function formatCoursesForDashboard(Collection $courses): SupportCollection
    {
        $courses = $courses
            ->filter(function (Course $course) {
                $enrollment = $course->enrollments->first();
                return $this->enrollmentValidator->checkEnrollment($enrollment);
            })
            ->map(function (Course $course) {
                $course = $this->dataReplacer->replaceCourseDataForGroup($course);

                $course->image = is_string($course->image)
                    ? Storage::disk('images')->url($course->image)
                    : Storage::disk('assets')->url('images/course-no-image.svg');

                return $course;
            });

        return $courses
            ->partition(function (Course $course) {
                return $course->finished_at === null || $course->finished_at >= Carbon::now();
            })
            ->map(function (Collection $group) {
                return $group->values();
            });
    }

    /**
     * @param \Modules\Learning\Models\Course $course
     * @return \Modules\Learning\Models\Course
     */
    public function formatCourse(Course $course): Course
    {
        $course = $this->dataReplacer->replaceCourseDataForGroup($course);

        $course->image = is_string($course->image)
            ? Storage::disk('images')->url($course->image)
            : Storage::disk('assets')->url('images/course-no-image.svg');

        $content = $course->content;
        $course->content = $this->contentHelper->prepareContent($content);

        $attachments = $this->dataReplacer->getCourseAttachments($course);
        $course->attachmentsCategories = $this->formatAttachments($attachments);

        // TODO: Realize a functional for checking is lesson completed
        $course->lessons->map(function (Lesson $lesson) {
            $lesson->completed = false;
            return $lesson;
        });

        unset(
            $course->attachments
        );

        return $course;
    }

    /**
     * @param \Modules\Learning\Models\Lesson\Lesson $lesson
     * @param int $studentId
     * @return \Modules\Learning\Models\Lesson\Lesson
     */
    public function formatLesson(Lesson $lesson, int $studentId): Lesson
    {
        $lesson = $this->dataReplacer->replaceLessonDataForGroup($lesson, $studentId);

        $content = $lesson->content;
        $lesson->content = $this->contentHelper->prepareContent($content);

        $lesson->attachmentsCategories = $this->formatAttachments($lesson->attachments);

        $youtubeUrlRegex = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
        $isYoutubeLink = preg_match($youtubeUrlRegex, $lesson->record_link, $matches);
        $lesson->record_type = $isYoutubeLink ? 'player' : 'iframe';

        $courseTeachers = $lesson->course->teachers;
        if (!$lesson->teacher instanceof User && $courseTeachers->count() === 1) {
            $courseTeacher = $courseTeachers->first();
            $lesson->teacher()->associate($courseTeacher);
        }

        unset(
            $lesson->course,
            $lesson->attachments,
            $lesson->created_at,
            $lesson->updated_at,
            $lesson->sort_order
        );

        return $lesson;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $attachments
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function formatAttachments(Collection $attachments): SupportCollection
    {
        return $attachments
            ->groupBy(function ($attachment) {
                return $attachment->category->name ?? 'Материалы для изучения';
            })
            ->map(function (SupportCollection $attachments, string $categoryName) {
                return [
                    'name' => $categoryName,
                    'attachments' => $attachments->map(function ($attachment) {
                        if ($attachment->type !== AttachmentTypes::TYPE_FILE) {
                            return $attachment;
                        }

                        $extension = pathinfo(parse_url($attachment->link, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $attachment->icon = in_array($extension, ['pdf', 'rar', 'zip', 'doc', 'docx', 'jpg']) ? $extension : 'default';
                        $attachment->link = Storage::disk('uploads')->url($attachment->link);
                        return $attachment;
                    })
                ];
            })
            ->values();
    }
}