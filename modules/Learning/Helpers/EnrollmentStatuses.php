<?php

namespace Modules\Learning\Helpers;

/**
 * Interface EnrollmentStatuses
 * @package Modules\Learning\Helpers
 */
interface EnrollmentStatuses
{
    /**
     * @var string
     */
    public const ACTIVE = 'Обучается';

    /**
     * @var string
     */
    public const INACTIVE = 'Отчислен';

    /**
     * @var string
     */
    public const SUSPENDED = 'Приостановлено';

    /**
     * @var string
     */
    public const NOT_STARTED = 'В ожидании старта';
}