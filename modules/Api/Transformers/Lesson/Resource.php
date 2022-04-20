<?php

namespace Modules\Api\Transformers\Lesson;

use Modules\Api\Transformers\BaseResource;
use Modules\Api\Transformers\User\Resource as UserResource;
use Modules\Learning\Helpers\LinksGenerator;

/**
 * Class Resource
 * @package Modules\Api\Transformers\Lesson
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
            'content' => $this->content,
            'published' => $this->published,
            'started_at' => $this->started_at,
            'link' => LinksGenerator::getLessonLink($this->course->id, $this->id),
            'teacher' => new UserResource($this->teacher),
        ];
    }
}
