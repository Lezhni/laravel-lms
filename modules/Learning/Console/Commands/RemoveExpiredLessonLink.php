<?php

namespace Modules\Learning\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Learning\Models\Lesson\Lesson;

class RemoveExpiredLessonLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lesson:remove-expired-links';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $intervalStart = Carbon::now()->subDays(3);
        $intervalEnd = Carbon::yesterday()->endOfDay();

        $finishedLessons = Lesson::query()
            ->whereNotNull('record_link')
            ->whereNotNull('content')
            ->whereBetween('started_at', [$intervalStart, $intervalEnd])
            ->get();

        foreach ($finishedLessons as $lesson) {
            $changed = false;
            $content = $lesson->content;
            foreach ($content as $i => $block) {
                if ($block['layout'] === 'block-zoom-room') {
                    unset($content[$i]);
                    $changed = true;
                    break;
                }
            }

            if ($changed) {
                $lesson->content = $content;
                $lesson->save();
            }
        }

        return Command::SUCCESS;
    }
}
