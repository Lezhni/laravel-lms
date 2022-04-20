<?php

namespace Modules\Learning\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Modules\Learning\Models\Lesson\Lesson;
use Modules\Learning\Notifications\Lesson\LessonStartSoon;

/**
 *
 */
class NotifyAboutLessonStart extends Command
{
    /**
     * @var int
     */
    protected const CHECK_IN_MINUTES = 15;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:closest-lessons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify students about closest lessons';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $closestDate = Carbon::now()->addMinutes(self::CHECK_IN_MINUTES);
        $closestLessons = Lesson::published()->with('course')->where('started_at', '<=', $closestDate);

        foreach ($closestLessons as $lesson) {
            $students = User::query()
                ->whereHas('activeEnrollments', function (Builder $q) use ($lesson) {
                    $q->where('course_id', $lesson->course->id);
                })
                ->get();
            if ($students->isEmpty()) {
                continue;
            }

            $lessonStartSoon = new LessonStartSoon($lesson);
            Notification::send($students, $lessonStartSoon);
        }

        return Command::SUCCESS;
    }
}
