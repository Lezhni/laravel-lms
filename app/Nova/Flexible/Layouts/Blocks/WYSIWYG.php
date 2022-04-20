<?php

namespace App\Nova\Flexible\Layouts\Blocks;

use Laravel\Nova\Fields\Trix;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

/**
 * Class WYSIWYG
 * @package App\Nova\Flexible\Layouts\Blocks
 */
class WYSIWYG extends Layout
{
    /**
     * @var string
     */
    protected $name = 'block-wysiwyg';

    /**
     * @var string
     */
    protected $title = 'WYSIWYG редактор';

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            Trix::make('Текст', 'text')
                ->rules(['required', 'string']),
        ];
    }
}