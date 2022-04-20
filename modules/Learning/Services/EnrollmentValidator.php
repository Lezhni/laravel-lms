<?php

namespace Modules\Learning\Services;

use Illuminate\Support\Carbon;
use Modules\Learning\Models\Enrollment;
use Modules\Learning\Models\Group\Group;

/**
 * Class EnrollmentValidator
 * @package Modules\Learning\Services
 */
class EnrollmentValidator
{
    /**
     * @var \Illuminate\Support\Carbon
     */
    protected Carbon $today;

    /**
     * EnrollmentValidator constructor.
     * @param \Modules\Learning\Models\Enrollment $enrollment
     */
    public function __construct(protected Enrollment $enrollment)
    {
        $this->today = Carbon::now();
    }

    /**
     * @param int $studentId
     * @param int $courseId
     * @return bool
     */
    public function isStudentEnrolled(int $studentId, int $courseId): bool
    {
        $enrollment = Enrollment::with('group')
            ->where('course_id', $courseId)
            ->where('student_id', $studentId)
            ->first();

        if (!$enrollment instanceof Enrollment) {
            return false;
        }

        return $this->checkEnrollment($enrollment);
    }

    /**
     * @param \Modules\Learning\Models\Enrollment $enrollment
     * @return bool
     */
    public function checkEnrollment(Enrollment $enrollment): bool
    {
        if ($enrollment->suspended) {
            return false;
        }

        $this->enrollment = $enrollment;

        return ($enrollment->group instanceof Group)
            ? $this->isGroupActive()
            : $this->isEnrollmentActive();
    }

    /**
     * @return bool
     */
    protected function isGroupActive(): bool
    {
        $startedAt = $this->enrollment->group->started_at;
        $finishedAt = $this->enrollment->group->access_closed_at;

        return
            $startedAt <= $this->today &&
            $finishedAt >= $this->today;
    }

    /**
     * @return bool
     */
    protected function isEnrollmentActive(): bool
    {
        $startedAt = $this->enrollment->started_at;
        $finishedAt = $this->enrollment->finished_at;

        return (
            ($startedAt === null && $finishedAt === null) ||
            ($startedAt <= $this->today && ($finishedAt >= $this->today || $finishedAt === null))
        );
    }
}