<?php

namespace Modules\Learning\Helpers;

/**
 * Interface AttachmentTypes
 * @package Modules\Learning\Helpers
 */
interface AttachmentTypes
{
    /**
     * @var string
     */
    public const TYPE_FILE = 'file';

    /**
     * @var string
     */
    public const TYPE_LINK = 'link';

    /**
     * @var string
     */
    public const TYPE_VIDEO = 'video';

    /**
     * @var string
     */
    public const TYPE_PAGE = 'page';

    /**
     * @var array
     */
    public const ALL = [
        self::TYPE_FILE => 'Файл',
        self::TYPE_LINK => 'Внешняя ссылка',
        self::TYPE_VIDEO => 'Ссылка на видео',
        self::TYPE_PAGE => 'Страница',
    ];
}