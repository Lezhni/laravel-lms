<?php

namespace Modules\Learning\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Lesson\Lesson;

/**
 *
 */
class CourseRepository
{
    /**
     * @param int $studentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStudentCourses(int $studentId): Collection
    {
        return Course::published()
            ->withCount(['lessons'])
            ->with(['teachers', 'enrollments.group'])
            ->with(['enrollments' => function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }])
            ->whereHas('enrollments', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->get();
    }

    /**
     * @param int $courseId
     * @return bool
     */
    public function isCourseExists(int $courseId): bool
    {
        return Course::published()
            ->where('id', $courseId)
            ->exists();
    }

    /**
     * @param int $courseId
     * @param int $studentId
     * @return \Modules\Learning\Models\Course|null
     */
    public function getStudentCourse(int $courseId, int $studentId): ?Course
    {
        return Course::published()
            ->select(['id', 'name', 'image', 'content', 'started_at', 'finished_at'])
            ->with([
                'teachers',
                'lessons.quizzes.previousQuiz',
                'lessons.homework',
                'enrollments.group.overriddenLessons'
            ])
            ->with(['enrollments' => function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }])
            ->whereHas('enrollments', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->find($courseId);
    }

    /**
     * @param int $courseId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLessons(int $courseId): Collection
    {
        return Lesson::published()
            ->with(['course.teachers', 'teacher'])
            ->where('course_id', $courseId)
            ->orderBy('started_at', 'asc')
            ->get();
    }

    /**
     * @param int $lessonId
     * @return \Modules\Learning\Models\Lesson\Lesson|null
     */
    public function getLesson(int $lessonId): ?Lesson
    {
        return Lesson::published()
            ->with(['course.teachers', 'teacher', 'attachments.category'])
            ->find($lessonId);
    }
}