<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class User
 * @package App\Http\Resources
 */
class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'avatar' => $this->avatarUrl,
                'is_admin' => $this->is_admin,
                'created_at' => $this->created_at->format('d.m.Y H:i:s'),
            ]
        ];
    }
}