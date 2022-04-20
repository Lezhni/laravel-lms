<?php

namespace Modules\Learning\Models\Attachment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 * @package Modules\Learning\Models\Attachment
 */
class Category extends Model
{
    /**
     * @var string
     */
    protected $table = 'courses_attachment_categories';

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
