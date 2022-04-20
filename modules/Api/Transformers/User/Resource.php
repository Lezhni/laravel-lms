<?php

namespace Modules\Api\Transformers\User;

use Modules\Api\Transformers\BaseResource;
use Modules\Api\Transformers\Role\Resource as RoleResource;

/**
 * Class Resource
 * @package Modules\Api\Transformers\User
 */
class Resource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'telegram' => $this->telegram,
            'roles' => RoleResource::collection($this->roles),
        ];
    }
}
