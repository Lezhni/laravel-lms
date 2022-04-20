<?php

namespace App\Nova\Flexible\Layouts\Blocks;

use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class Video extends Layout
{
    /**
     * @var string
     */
    protected $name = 'block-video';

    /**
     * @var string
     */
    protected $title = 'Видео';

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