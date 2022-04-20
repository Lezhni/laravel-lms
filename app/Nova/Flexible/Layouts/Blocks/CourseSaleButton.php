<?php

namespace App\Nova\Flexible\Layouts\Blocks;

use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

/**
 * Class CourseSaleButton
 * @package App\Nova\Flexible\Layouts\Blocks
 */
class CourseSaleButton extends Layout
{
    /**
     * @var string
     */
    protected $name = 'block-sale-button';

    /**
     * @var string
     */
    protected $title = 'Блок покупки курса';

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            Text::make('Ссылка на покупку курса', 'buy_link')
                ->rules(['required', 'url']),

            Text::make('Ссылка на информацию о курсе', 'info_link')
                ->rules(['required', 'url']),
        ];
    }
}