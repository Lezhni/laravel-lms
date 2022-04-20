<?php

namespace Modules\Learning\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Learning\Console\Commands\NotifyAboutLessonStart;
use Modules\Learning\Console\Commands\RemoveExpiredLessonLink;
use Modules\Learning\Helpers\CourseHelper;
use Modules\Learning\Helpers\HomeworkHelper;
use Modules\Learning\Helpers\QuizHelper;
use Modules\Learning\Models\Attachment\Attachment as CourseAttachment;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Enrollment;
use Modules\Learning\Models\Group\Group;
use Modules\Learning\Models\Lesson\Attachment\Attachment as LessonAttachment;
use Modules\Learning\Models\Lesson\Homework\ChatMessage;
use Modules\Learning\Models\Lesson\Homework\Grade;
use Modules\Learning\Models\Lesson\Lesson;
use Modules\Learning\Observers\AttachmentObserver as CourseAttachmentObserver;
use Modules\Learning\Observers\CourseObserver;
use Modules\Learning\Observers\EnrollmentObserver;
use Modules\Learning\Observers\Group\GroupObserver;
use Modules\Learning\Observers\Lesson\AttachmentObserver as LessonAttachmentObserver;
use Modules\Learning\Observers\Lesson\Homework\ChatMessageObserver;
use Modules\Learning\Observers\Lesson\Homework\GradeObserver;
use Modules\Learning\Observers\Lesson\LessonObserver;
use Modules\Learning\Repositories\CourseRepository;
use Modules\Learning\Repositories\HomeworkRepository;
use Modules\Learning\Repositories\QuizRepository;
use Modules\Learning\Services\EnrollmentValidator;
use Modules\Learning\Services\HomeworkService;
use Modules\Learning\Services\LessonService;
use Modules\Learning\Services\QuizService;

/**
 * Class CoursesServiceProvider
 * @package Modules\Learning\Providers
 */
class LearningServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(NovaServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ScheduleServiceProvider::class);

        $this->app->singleton(CourseHelper::class);
        $this->app->singleton(QuizHelper::class);
        $this->app->singleton(HomeworkHelper::class);

        $this->app->singleton(QuizService::class);
        $this->app->singleton(HomeworkService::class);
        $this->app->singleton(LessonService::class);
        $this->app->singleton(EnrollmentValidator::class);

        $this->app->singleton(CourseRepository::class);
        $this->app->singleton(QuizRepository::class);
        $this->app->singleton(HomeworkRepository::class);

        $this->commands([
            NotifyAboutLessonStart::class,
            RemoveExpiredLessonLink::class,
        ]);
    }

    /**
     * @return void
     */
    public function boot()
    {
        Course::observe(CourseObserver::class);
        CourseAttachment::observe(CourseAttachmentObserver::class);
        Group::observe(GroupObserver::class);
        Enrollment::observe(EnrollmentObserver::class);
        Lesson::observe(LessonObserver::class);
        LessonAttachment::observe(LessonAttachmentObserver::class);
        Grade::observe(GradeObserver::class);
        ChatMessage::observe(ChatMessageObserver::class);
    }
}
