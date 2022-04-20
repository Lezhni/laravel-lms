<?php

namespace App\Nova\Flexible\Presets;

use App\Helpers\BlockEditor\Helper;
use Illuminate\Support\Facades\App;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

/**
 * Class BlockEditorPreset
 * @package App\Nova\Flexible\Presets
 */
class BlockEditor extends Preset
{
    /**
     * @var \App\Helpers\BlockEditor\Helper
     */
    private Helper $helper;

    /**
     * BlockEditorPreset constructor.
     * @param \App\Helpers\BlockEditor\Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Execute the preset configuration
     *
     * @return void
     * @throws \Exception
     */
    public function handle(Flexible $field)
    {
        $field->button('Добавить блок');

        $blocks = $this->helper->getBlocksList();
        foreach ($blocks as $blockName) {
            $block = App::make($blockName);
            $field->addLayout($block->title(), $block->name(), $block->fields());
        }
    }
}