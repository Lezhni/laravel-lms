<?php

namespace Modules\Learning\Nova\Flexible\Presets;

use App\Nova\Flexible\Layouts\Blocks\Image;
use App\Nova\Flexible\Layouts\Blocks\Textarea;
use Illuminate\Support\Facades\App;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

class QuizQuestionContent extends Preset
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

        foreach ([Textarea::class, Image::class] as $blockName) {
            $block = App::make($blockName);
            $field->addLayout($block->title(), $block->name(), $block->fields());
        }
    }
}