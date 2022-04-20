<?php

namespace Modules\Learning\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Modules\Learning\Models\Attachment\Attachment;
use Modules\Learning\Models\Group\Group;
use Modules\Learning\Models\Lesson\Homework\Homework;
use Modules\Learning\Models\Lesson\Lesson;
use Modules\Learning\Models\Quiz\Quiz;

/**
 * Class Course
 * @property \Illuminate\Database\Eloquent\Collection teachers
 * @package Modules\Learning\Models
 */
class Course extends Model
{
    /**
     * @var string
     */
    public const IMAGES_FOLDER = 'courses';

    /**
     * @var string
     */
    protected $table = 'courses';

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var string[]
     */
    protected $casts = [
        'content' => 'array',
        'published' => 'boolean',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'access_closed_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons(): HasMany
    {
        return $this
            ->hasMany(Lesson::class, 'course_id')
            ->select(['id', 'name', 'finished', 'course_id', 'started_at'])
            ->where('lessons.published', true)
            ->ordered();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allLessons(): HasMany
    {
        return $this
            ->hasMany(Lesson::class, 'course_id')
            ->ordered();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function homeworks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Homework::class,
            Lesson::class,
            'course_id',
            'lesson_id'
        )
            ->where('lessons_homeworks.published', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function allHomeworks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Homework::class,
            Lesson::class,
            'course_id',
            'lesson_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function allQuizzes(): HasManyThrough
    {
        return $this->hasManyThrough(Quiz::class, Lesson::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'courses_teachers',
            'course_id',
            'teacher_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'courses_enrollments', 'course_id', 'student_id')
            ->using(CourseStudentPivot::class)
            ->withPivot(['started_at', 'finished_at']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments(): HasMany
    {
        return $this
            ->hasMany(Attachment::class, 'course_id')
            ->ordered();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}