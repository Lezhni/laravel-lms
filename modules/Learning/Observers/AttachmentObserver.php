<?php

namespace Modules\Learning\Observers;

use Illuminate\Support\Facades\Storage;
use Modules\Learning\Helpers\AttachmentTypes;
use Modules\Learning\Models\Attachment\Attachment;

/**
 *
 */
class AttachmentObserver
{
    /**
     * @param \Modules\Learning\Models\Attachment\Attachment $attachment
     */
    public function saving(Attachment $attachment)
    {
        $oldFile = Attachment::where('id', $attachment->id)->where('type', AttachmentTypes::TYPE_FILE)->value('link');
        if (! $oldFile || $oldFile === $attachment->link) { return; }

        Storage::disk('uploads')->delete($oldFile);
    }

    /**
     * @param \Modules\Learning\Models\Attachment\Attachment $attachment
     */
    public function deleted(Attachment $attachment)
    {
        if ($attachment->link == null || $attachment->type === AttachmentTypes::TYPE_LINK) {
            return;
        }

        Storage::disk('uploads')->delete($attachment->link);
    }
}