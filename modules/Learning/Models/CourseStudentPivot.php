<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CourseStudentPivot
 * @package Modules\Learning\Models
 */
class CourseStudentPivot extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'courses_enrollments';

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var string[]
     */
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}