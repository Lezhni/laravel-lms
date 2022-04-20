<?php

namespace Modules\Learning\Models\Lesson;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Modules\Learning\Models\Course;
use Modules\Learning\Models\Lesson\Attachment\Attachment;
use Modules\Learning\Models\Lesson\Homework\Grade;
use Modules\Learning\Models\Lesson\Homework\Homework;
use Modules\Learning\Models\Quiz\Quiz;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property \Carbon\CarbonInterface started_at
 * @property \Modules\Learning\Models\Course course
 * @property \Modules\Learning\Models\Lesson\Homework\Homework homework
 * @property \App\Models\User teacher
 * @property \Illuminate\Database\Eloquent\Collection attachments
 * @property \Illuminate\Database\Eloquent\Collection quizzes
 */
class Lesson extends Model implements Sortable
{
    use SortableTrait;

    /**
     * @var string
     */
    protected $table = 'lessons';

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var string[]
     */
    protected $hidden = [
        'course_id',
        'teacher_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'course_id' => 'integer',
        'published' => 'boolean',
        'finished' => 'boolean',
        'content' => 'array',
        'sort_order' => 'integer',
        'started_at' => 'datetime',
    ];

    /**
     * @var string[]
     */
    public array $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
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
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments(): HasMany
    {
        return $this
            ->hasMany(Attachment::class, 'lesson_id')
            ->ordered();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function homework(): HasOne
    {
        return $this->hasOne(Homework::class, 'lesson_id')
            ->published();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function grades(): HasManyThrough
    {
        return $this->hasManyThrough(
            Grade::class,
            Homework::class,
            'lesson_id',
            'homework_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'lesson_id')
            ->select(['id', 'name', 'lesson_id', 'previous_quiz_id'])
            ->published();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allQuizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'lesson_id');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFinished(Builder $query): Builder
    {
        return $query->where(function (Builder $q1) {
            $q1->where('finished', true)->orWhere(function (Builder $q2) {
                $q2->where('started_at', '<=', Carbon::now())->whereNotNull('record_link');
            });
        });
    }

    /**
     * @param string|null $value
     * @return string
     */
    public function getRecordNameAttribute(string|null $value): string
    {
        return $value ?? 'Запись занятия';
    }
}
