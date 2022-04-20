<?php

namespace Modules\Api\Transformers\Role;

use Modules\Api\Transformers\BaseResource;

/**
 * Class Resource
 * @package Modules\Api\Transformers\Role
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
            'title' => $this->title,
        ];
    }
}
