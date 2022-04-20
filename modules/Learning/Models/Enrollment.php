<?php

namespace Modules\Learning\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Learning\Helpers\EnrollmentStatuses;
use Modules\Learning\Models\Group\Group;

/**
 * @property \Modules\Learning\Models\Course course
 * @property \App\Models\User student
 */
class Enrollment extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'courses_enrollments';

    /**
     * @var string[]
     */
    protected $fillable = [
        'student_id',
        'course_id',
        'group_id',
        'started_at',
        'finished_at',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'status',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'course_id',
        'student_id',
        'group_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'suspended' => 'boolean',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * @return string
     */
    public function getStatusAttribute(): string
    {
        if ($this->suspended) {
            return EnrollmentStatuses::SUSPENDED;
        }

        $startedAt = ($this->group_id == null) ? $this->started_at : $this->group->started_at;
        $finishedAt = ($this->group_id == null) ? $this->finished_at : $this->group->finished_at;
        $today = Carbon::now();

        if ($startedAt > $today) {
            return EnrollmentStatuses::NOT_STARTED;
        }

        if (
            ($startedAt === null && $finishedAt === null) ||
            ($startedAt <= $today && ($finishedAt >= $today || $finishedAt === null))
        ) {
            return EnrollmentStatuses::ACTIVE;
        }

        return EnrollmentStatuses::INACTIVE;
    }
}
