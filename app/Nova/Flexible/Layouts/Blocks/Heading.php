<?php

namespace App\Nova\Flexible\Layouts\Blocks;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

/**
 * Class Heading
 * @package App\Nova\Flexible\Layouts\Blocks
 */
class Heading extends Layout
{
    /**
     * @var string
     */
    protected $name = 'block-heading';

    /**
     * @var string
     */
    protected $title = 'Заголовок';

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            Select::make('Тип заголовка', 'type')
                ->options(['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6'])
                ->default('h1')
                ->rules(['required', 'in:h1,h2,h3,h4,h5,h6']),

            Text::make('Текст', 'text')
                ->rules(['required', 'string']),
        ];
    }
}