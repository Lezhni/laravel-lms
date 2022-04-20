<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 *
 */
class UploadsService
{
    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param bool $generateFilename
     * @return string
     */
    public function storeUpload(UploadedFile $file, string $path, bool $generateFilename = false): string
    {
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        if (!$generateFilename) {
            $flushedFilename = Str::slug($filename, '_');
            $now = Carbon::now()->getTimestamp();
            $newFilename = "{$flushedFilename}_{$now}";
        } else {
            $newFilename = md5($filename . time());
        }

        $newFilename .= ".{$extension}";
        Storage::disk('uploads')->putFileAs($path, $file, $newFilename);

        return $newFilename;
    }
}