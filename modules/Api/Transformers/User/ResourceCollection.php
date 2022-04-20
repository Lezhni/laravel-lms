<?php

namespace Modules\Api\Transformers\User;

use Modules\Api\Transformers\BaseResourceCollection;

/**
 * Class ResourceCollection
 * @package Modules\Api\Transformers\User
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
