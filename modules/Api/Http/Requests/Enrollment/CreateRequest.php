<?php

namespace Modules\Api\Http\Requests\Enrollment;

use Modules\Api\Http\Requests\ApiRequest;

/**
 *
 */
class CreateRequest extends ApiRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return
            parent::authorize() &&
            $this->accessToken->hasPermissionTo('enrollments.create');
    }

    /**
     * @return \string[][]
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'group_id' => ['nullable', 'exists:groups,id'],
            'started_at' => ['nullable', 'required_with:finished_at', 'date'],
            'finished_at' => ['nullable', 'after:started_at', 'date'],
        ];
    }
}