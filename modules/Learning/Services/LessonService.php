<?php

namespace Modules\Learning\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Learning\Models\Lesson\Lesson;

/**
 *
 */
class LessonService
{
    /**
     * @param \Modules\Learning\Models\Lesson\Lesson $lesson
     * @return bool
     */
    public function isOpened(Lesson $lesson): bool
    {
        $user = Auth::user();
        $currentDateTime = Carbon::now();

        return
            $user->is_admin ||
            $lesson->started_at == null ||
            $lesson->started_at->startOfDay()->lte($currentDateTime);
    }
}