<?php

namespace App\Nova\Flexible\Layouts\Blocks;

use Laravel\Nova\Fields\File as FileField;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class File extends Layout
{
    /**
     * @var string
     */
    protected $name = 'block-file';

    /**
     * @var string
     */
    protected $title = 'Прикрепленный файл';

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            FileField::make('Файл', 'file')
                ->disk('uploads')
                ->path('content')
                ->rules(['max:30000']),

            Text::make('Название файла', 'name')
                ->rules(['required', 'string', 'max:255'])
        ];
    }
}