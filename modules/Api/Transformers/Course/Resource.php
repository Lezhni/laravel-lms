<?php

namespace Modules\Api\Transformers\Course;

use Illuminate\Support\Facades\Storage;
use Modules\Api\Transformers\BaseResource;
use Modules\Learning\Helpers\LinksGenerator;

/**
 * Class Resource
 * @package Modules\Api\Transformers\Course
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
        $image = is_string($this->image)
            ? Storage::disk('images')->url($this->image)
            : Storage::disk('assets')->url('images/course-no-image.svg');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'published' => $this->published,
            'image' => $image,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'link' => LinksGenerator::getCourseLink($this->id),
        ];
    }
}
