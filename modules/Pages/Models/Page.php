<?php

namespace Modules\Pages\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class Page extends Model
{
    /**
     * @var string
     */
    protected $table = 'pages';

    /**
     * @var string[]
     */
    protected $hidden = [
        'category_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'published' => 'boolean',
        'content' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
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
