<?php

namespace App\Nova\Flexible\Layouts\Blocks;

use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Text;
use R64\NovaImageCropper\ImageCropper;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class Image extends Layout
{
    /**
     * @var string
     */
    protected $name = 'block-image';

    /**
     * @var string
     */
    protected $title = 'Изображение';

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            ImageCropper::make('Изображение', 'image')
                ->disk('uploads')
                ->path('content')
                ->aspectRatio(false)
                ->preview(function ($value) {
                    return $value ? Storage::disk('uploads')->url($value) : null;
                }),

            Text::make('Описание (alt)', 'alt')
                ->rules(['nullable', 'string']),
        ];
    }

}