<?php

namespace Modules\Calendar\Helpers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Modules\Calendar\Services\Group\DataReplacer;
use Modules\Learning\Models\Lesson\Lesson;

/**
 *
 */
class CalendarHelper
{
    /**
     * CalendarHelper constructor.
     * @param \Modules\Calendar\Services\Group\DataReplacer $dataReplacer
     */
    public function __construct(protected DataReplacer $dataReplacer)
    {
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $lessons
     * @param int $studentId
     * @return \Illuminate\Support\Collection
     */
    public function convertLessonsToEvents(EloquentCollection $lessons, int $studentId): Collection
    {
        $lessons = $this->dataReplacer->replaceDatesForGroup($lessons, $studentId);

        return $lessons
            ->filter(function (Lesson $lesson) {
                return $lesson->started_at != null;
            })
            ->values()
            ->map(function (Lesson $lesson) {
                $courseTeachers = $lesson->course->teachers;
                if (!$lesson->teacher instanceof User && $courseTeachers->count() === 1) {
                    $courseTeacher = $courseTeachers->first();
                    $lesson->teacher()->associate($courseTeacher);
                }

                return [
                    'title' => $lesson->name,
                    'date' => $lesson->started_at,
                    'type' => EventType::LESSON,
                    'available' => ($lesson->started_at->startOfDay() <= Carbon::now()),
                    'teacher' => $lesson->teacher?->name,
                    'link' => [
                        'title' => 'Перейти к занятию',
                        'url' => [
                            'course_id' => $lesson->course->id,
                            'lesson_id' => $lesson->id,
                        ],
                    ]
                ];
            });
    }
}