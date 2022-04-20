<?php

namespace App\Helpers\BlockEditor;

use App\Nova\Flexible\Layouts\Blocks\CourseSaleButton;
use App\Nova\Flexible\Layouts\Blocks\File;
use App\Nova\Flexible\Layouts\Blocks\Heading;
use App\Nova\Flexible\Layouts\Blocks\Image;
use App\Nova\Flexible\Layouts\Blocks\Textarea;
use App\Nova\Flexible\Layouts\Blocks\Video;
use App\Nova\Flexible\Layouts\Blocks\WYSIWYG;
use Illuminate\Support\Facades\Storage;

/**
 * Class Helpers
 * @package App\Helpers\BlockEditor
 */
final class Helper
{
    /**
     * @return string[]
     */
    public function getBlocksList(): array
    {
        return [
            Heading::class,
            Textarea::class,
            WYSIWYG::class,
            Image::class,
            File::class,
            Video::class,
            CourseSaleButton::class,
        ];
    }

    /**
     * @param array|null $content
     * @return array
     */
    public function prepareContent(array $content = null): array
    {
        if (!is_array($content)) {
            return [];
        }

        foreach ($content as &$block) {
            if ($block['layout'] === 'block-image') {
                $block['attributes']['image'] = Storage::disk('uploads')->url($block['attributes']['image']);
            }
            if ($block['layout'] === 'block-file') {
                $block['attributes']['file'] = Storage::disk('uploads')->url($block['attributes']['file']);
            }
            if ($block['layout'] == 'block-video') {
                $youtubeUrlRegex = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
                $isYoutubeLink = preg_match($youtubeUrlRegex, $block['attributes']['link'], $matches);
                $block['attributes']['type'] = $isYoutubeLink ? 'player' : 'iframe';
            }
        }

        return $content;
    }
}