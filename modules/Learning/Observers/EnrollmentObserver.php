<?php

namespace Modules\Learning\Observers;

use Modules\Learning\Models\Enrollment;
use Modules\Learning\Notifications\EnrolledToCourse;
use Modules\Learning\Notifications\EnrollmentSuspended;
use Modules\Learning\Notifications\EnrollmentUnsuspended;
use Modules\Learning\Notifications\UnrolledFromCourse;

/**
 *
 */
class EnrollmentObserver
{
    /**
     * @param \Modules\Learning\Models\Enrollment $enrollment
     */
    public function saving(Enrollment $enrollment)
    {
        $enrollment->load(['course', 'student']);
        $previousEnrollment = Enrollment::find($enrollment->id);

        $this->notifyAboutEnrollment($enrollment, $previousEnrollment);
        $this->notifyAboutSuspending($enrollment, $previousEnrollment);
    }

    /**
     * @param \Modules\Learning\Models\Enrollment $enrollment
     * @param \Modules\Learning\Models\Enrollment|null $previousEnrollment
     */
    protected function notifyAboutEnrollment(Enrollment $enrollment, ?Enrollment $previousEnrollment)
    {
        if ($previousEnrollment && $previousEnrollment->is_active === $enrollment->is_active) {
            return;
        }

        $notification = $enrollment->is_active
            ? new EnrolledToCourse($enrollment->course)
            : new UnrolledFromCourse($enrollment->course);

        $enrollment->student->notify($notification);
    }

    /**
     * @param \Modules\Learning\Models\Enrollment $enrollment
     * @param \Modules\Learning\Models\Enrollment|null $previousEnrollment
     */
    protected function notifyAboutSuspending(Enrollment $enrollment, ?Enrollment $previousEnrollment)
    {
        if ($previousEnrollment && $previousEnrollment->suspended === $enrollment->suspended) {
            return;
        }

        $notification = $enrollment->suspended
            ? new EnrollmentSuspended($enrollment->course)
            : new EnrollmentUnsuspended($enrollment->course);

        $enrollment->student->notify($notification);
    }
}