<?php

namespace Modules\Learning\Providers;

use App\Providers\AuthServiceProvider as AppAuthServiceProvider;

/**
 *
 */
class AuthServiceProvider extends AppAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Learning\Models\Course::class => \Modules\Learning\Policies\CoursePolicy::class,
        \Modules\Learning\Models\Group\Group::class => \Modules\Learning\Policies\Group\GroupPolicy::class,
        \Modules\Learning\Models\Group\GroupLesson::class => \Modules\Learning\Policies\Group\GroupLessonPolicy::class,
        \Modules\Learning\Models\Attachment\Attachment::class => \Modules\Learning\Policies\AttachmentPolicy::class,
        \Modules\Learning\Models\Enrollment::class => \Modules\Learning\Policies\EnrollmentPolicy::class,
        \Modules\Learning\Models\Lesson\Lesson::class => \Modules\Learning\Policies\Lesson\LessonPolicy::class,
        \Modules\Learning\Models\Lesson\Attachment\Attachment::class => \Modules\Learning\Policies\Lesson\AttachmentPolicy::class,
        \Modules\Learning\Models\Lesson\Homework\Homework::class => \Modules\Learning\Policies\Lesson\Homework\HomeworkPolicy::class,
        \Modules\Learning\Models\Lesson\Homework\Grade::class => \Modules\Learning\Policies\Lesson\Homework\GradePolicy::class,
        \Modules\Learning\Models\Quiz\Quiz::class => \Modules\Learning\Policies\Quiz\QuizPolicy::class,
        \Modules\Learning\Models\Quiz\Question::class => \Modules\Learning\Policies\Quiz\QuestionPolicy::class,
        \Modules\Learning\Models\Quiz\Answer::class => \Modules\Learning\Policies\Quiz\AnswerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}