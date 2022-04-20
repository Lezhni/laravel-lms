<?php

namespace CreaceptLms\HomeworkChat;

use Laravel\Nova\Fields\Field;

/**
 *
 */
class HomeworkChat extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'homework-chat';

    /**
     * @param int|null $gradeId
     * @return $this
     */
    public function grade(?int $gradeId): HomeworkChat
    {
        return $this->withMeta([
            'gradeId' => $gradeId,
        ]);
    }
}
