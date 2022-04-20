<?php

namespace Modules\Learning\Models\Lesson\Homework;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property \Modules\Learning\Models\Lesson\Homework\ChatMessage message
 */
class ChatAttachment extends Model
{
    /**
     * @var string
     */
    public const UPLOADS_FOLDER = 'homeworks';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'lessons_homeworks_attachments';

    /**
     * @var string[]
     */
    protected $hidden = [
        'message_id',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'link',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'message_id');
    }

    /**
     * @return string|null
     */
    public function getLinkAttribute(): ?string
    {
        if (!$this->filename) {
            return null;
        }

        $filePath = self::UPLOADS_FOLDER . '/' . $this->filename;
        return Storage::disk('uploads')->url($filePath);
    }
}
