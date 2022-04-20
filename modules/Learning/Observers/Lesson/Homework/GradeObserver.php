<?php

namespace Modules\Learning\Observers\Lesson\Homework;

use Modules\Learning\Models\Lesson\Homework\Grade;
use Modules\Learning\Notifications\Lesson\Homework\HomeworkChecked;

/**
 *
 */
class GradeObserver
{
    /**
     * @param \Modules\Learning\Models\Lesson\Homework\Grade $grade
     */
    public function updated(Grade $grade)
    {
        if ($grade->grade == null || $grade->grade <= 0) {
            return;
        }

        $grade->load('student');

        $homeworkChecked = new HomeworkChecked($grade);
        $grade->student->notify($homeworkChecked);
    }
}