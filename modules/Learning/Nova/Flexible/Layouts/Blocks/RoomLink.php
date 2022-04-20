<?php

namespace Modules\Learning\Nova\Flexible\Layouts\Blocks;

use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

/**
 *
 */
class RoomLink extends Layout
{
    /**
     * @var string
     */
    protected $name = 'block-zoom-room';

    /**
     * @var string
     */
    protected $title = 'Ссылка на вебинар/занятие';

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            Text::make('Ссылка', 'link')
                ->rules(['required', 'url', 'max:255']),
        ];
    }
}