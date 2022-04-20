<?php

namespace App\Nova\Flexible\Layouts\Blocks;

use Whitecube\NovaFlexibleContent\Layouts\Layout;

/**
 * Class Textarea
 * @package App\Nova\Flexible\Layouts\Blocks
 */
class Textarea extends Layout
{
    /**
     * @var string
     */
    protected $name = 'block-textarea';

    /**
     * @var string
     */
    protected $title = 'Текст';

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            \Laravel\Nova\Fields\Textarea::make('Текст', 'text')
                ->rules(['required', 'string']),
        ];
    }
}