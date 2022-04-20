<?php

namespace Modules\Api\Transformers\Lesson;

use Modules\Api\Transformers\BaseResourceCollection;

/**
 * Class ResourceCollection
 * @package Modules\Api\Transformers\Lesson
 */
class ResourceCollection extends BaseResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'data' => Resource::collection($this->collection),
        ];
    }
}
