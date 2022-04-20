<?php

namespace App\Nova\Actions;

use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Throwable;

/**
 * Class PublishResource
 * @package App\Nova\Actions
 */
class PublishResource extends Action
{
    /**
     * @var string
     */
    public $name = 'Опубликовать';

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return array|string[]|void
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            try {
                $model->published = true;
                $model->saveOrFail();
            } catch (Throwable $e) {
                return Action::danger('Произошла ошибка, попробуйте еще раз');
            }
        }
    }
}
