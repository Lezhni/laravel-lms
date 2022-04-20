<?php

namespace Modules\Api\Transformers\Group;

use Modules\Api\Transformers\BaseResource;

/**
 * Class Resource
 * @package Modules\Api\Transformers\Group
 */
class Resource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
