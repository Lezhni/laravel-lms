<?php

namespace Modules\Api\Transformers\Enrollment;

use Modules\Api\Transformers\BaseResource;

/**
 * Class Resource
 * @package Modules\Api\Transformers\Enrollment
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
            'student_id' => $this->student_id,
            'course_id' => $this->course_id,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
        ];
    }
}
