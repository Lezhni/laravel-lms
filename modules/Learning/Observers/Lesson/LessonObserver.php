<?php

namespace Modules\Learning\Observers\Lesson;

use Illuminate\Support\Carbon;
use Modules\Learning\Models\Lesson\Lesson;

class LessonObserver
{
    /**
     * @param \Modules\Learning\Models\Lesson\Lesson $lesson
     */
    public function updating(Lesson $lesson)
    {
        $this->checkIsFinished($lesson);
    }

    /**
     * @param \Modules\Learning\Models\Lesson\Lesson $lesson
     */
    protected function checkIsFinished(Lesson $lesson)
    {
        $currentDateTime = Carbon::now();

        if ($lesson->finished || $lesson->started_at == null) {
            return;
        }

        $lesson->finished =
            $lesson->started_at->lt($currentDateTime) &&
            $lesson->record_link !== null;
    }
}