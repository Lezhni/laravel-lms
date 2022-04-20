<?php

namespace Modules\Notifications\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Modules\Notifications\Helpers\DTO\Notification as Entity;
use Modules\Notifications\Notifications\CustomNotification;

/**
 * Class NotifyStudents
 * @package Modules\Notifications\Nova\Actions
 */
class NotifyStudents extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * @var string
     */
    public $name = 'Добавить уведомление';

    /**
     * @var int
     */
    public static $chunkCount = 9999; // TODO: Change large chunk's count to native Laravel method for large data

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return array
     */
    public function handle(ActionFields $fields, Collection $models): array
    {
        $text = $fields->get('text');
        $link = $fields->get('link');

        $entity = new Entity($text, $link);
        $notification = new CustomNotification($entity);

        Notification::send($models, $notification);

        return Action::message('Уведомление отправлено');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Trix::make('Текст уведомления', 'text')
                ->rules(['required', 'string']),

            Text::make('Ссылка', 'link')
                ->rules(['nullable', 'url']),
        ];
    }
}
