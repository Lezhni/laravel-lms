<?php

namespace Modules\Learning\Observers;

use Illuminate\Support\Facades\Storage;
use Modules\Learning\Models\Course;

/**
 * Class CourseObserver
 * @package Modules\Learning\Observers
 */
class CourseObserver
{
    /**
     * @param \Modules\Learning\Models\Course $course
     */
    public function saving(Course $course)
    {
        if ($course->access_closed_at == null) {
            $course->access_closed_at = $course->finished_at;
        }
    }

    /**
     * @param \Modules\Learning\Models\Course $course
     */
    public function deleted(Course $course)
    {
        Storage::disk('images')->delete($course->image);
    }
}