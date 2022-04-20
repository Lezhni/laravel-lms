<?php

namespace Modules\Pages\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 */
class Category extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'pages_categories';

    /**
     * @var string[]
     */
    protected $casts = [
        'published' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'category_id')
            ->published()
            ->select(['name', 'alias', 'category_id']);
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