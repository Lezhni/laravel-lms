<?php

namespace Modules\Notifications\Helpers\DTO;

use UnexpectedValueException;

/**
 * Class Notification
 * @package Modules\Notifications\Helpers\Entities
 */
class Notification
{
    /**
     * @var string|null
     */
    protected ?string $link;

    /**
     * Notification constructor.
     *
     * @param string $text
     * @param string|null $link
     * @throws \UnexpectedValueException
     */
    public function __construct(protected string $text, ?string $link)
    {
        if ($link != null && !filter_var($link, FILTER_VALIDATE_URL)) {
            throw new UnexpectedValueException('Notification\'s link is not valid url');
        }
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }
}