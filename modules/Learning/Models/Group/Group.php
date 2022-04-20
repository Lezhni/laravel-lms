<?php

namespace Modules\Learning\Models\Group;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Learning\Models\Attachment\Attachment;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Lesson\Lesson;

/**
 * Class Group
 * @property \Illuminate\Database\Eloquent\Collection overriddenLessons
 * @package Modules\Learning\Models\Group
 */
class Group extends Model
{
    /**
     * @var string
     */
    protected $table = 'groups';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'course_id',
        'started_at',
        'finished_at',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'course_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'access_closed_at' => 'datetime',
        'course_content' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'courses_enrollments',
             'group_id',
            'student_id',
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lessons():  BelongsToMany
    {
        return $this
            ->belongsToMany(Lesson::class, 'groups_lessons')
            ->withPivot(['started_at', 'lesson_link', 'record_link']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function overriddenLessons(): HasMany
    {
        return $this->hasMany(GroupLesson::class, 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attachments(): BelongsToMany
    {
        return $this->belongsToMany(
            Attachment::class,
            'groups_attachments',
            'group_id',
            'attachment_id',
        );
    }
}
