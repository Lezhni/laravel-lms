<?php

namespace Modules\Learning\Nova\Flexible\Presets;

use App\Nova\Flexible\Layouts\Blocks\CourseSaleButton;
use App\Nova\Flexible\Layouts\Blocks\File;
use App\Nova\Flexible\Layouts\Blocks\Heading;
use App\Nova\Flexible\Layouts\Blocks\Image;
use App\Nova\Flexible\Layouts\Blocks\Textarea;
use App\Nova\Flexible\Layouts\Blocks\Video;
use App\Nova\Flexible\Layouts\Blocks\WYSIWYG;
use Illuminate\Support\Facades\App;
use Modules\Learning\Nova\Flexible\Layouts\Blocks\RoomLink;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

/**
 *
 */
class LessonContent extends Preset
{
    /**
     * Execute the preset configuration
     *
     * @return void
     * @throws \Exception
     */
    public function handle(Flexible $field)
    {
        $field->button('Добавить блок');

        $availableBlocks = [
            Heading::class,
            Textarea::class,
            Image::class,
            WYSIWYG::class,
            RoomLink::class,
            File::class,
            Video::class,
            CourseSaleButton::class,
        ];

        foreach ($availableBlocks as $blockName) {
            $block = App::make($blockName);
            $field->addLayout($block->title(), $block->name(), $block->fields());
        }
    }
}