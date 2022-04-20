<?php

namespace Modules\Learning\Models\Lesson\Attachment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 * @package Modules\Learning\Models\Lesson\Attachment
 */
class Category extends Model
{
    /**
     * @var string
     */
    protected $table = 'lessons_attachment_categories';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'category_id');
    }
}
