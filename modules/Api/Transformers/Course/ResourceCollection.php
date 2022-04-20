<?php

namespace Modules\Api\Transformers\Course;

use Modules\Api\Transformers\BaseResourceCollection;

/**
 * Class ResourceCollection
 * @package Modules\Api\Transformers\Course
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
