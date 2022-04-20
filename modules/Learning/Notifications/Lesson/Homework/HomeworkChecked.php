<?php

namespace Modules\Learning\Notifications\Lesson\Homework;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Learning\Helpers\LinksGenerator;
use Modules\Learning\Models\Lesson\Homework\Grade;

/**
 *
 */
class HomeworkChecked extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * @param \Modules\Learning\Models\Lesson\Homework\Grade $grade
     */
    public function __construct(protected Grade $grade)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        $this->grade->load(['student', 'homework', 'lesson.course']);

        $lessonName = $this->grade->lesson->name;
        $maxGrade = $this->grade->homework->max_grade;
        $grade = $this->grade->grade . ' ' . trans_choice('балл|балла|баллов', $this->grade->grade);

        $courseId = $this->grade->lesson->course->id;
        $lessonId = $this->grade->lesson->id;
        $homeworkLink = LinksGenerator::getHomeworkLink($courseId, $lessonId);

        return [
            'message' => "Преподаватель оценил домашнее задание \"{$lessonName}\" на {$grade} из {$maxGrade}",
            'link' => $homeworkLink,
        ];
    }

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType(): string
    {
        return 'student.homework-checked';
    }
}
