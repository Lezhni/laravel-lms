<?php

namespace Modules\Api\Http\Requests\Enrollment;

use Modules\Api\Http\Requests\ApiRequest;

/**
 *
 */
class UpdateRequest extends ApiRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return
            parent::authorize() &&
            $this->accessToken->hasPermissionTo('enrollments.update');
    }

    /**
     * @return \string[][]
     */
    public function rules(): array
    {
        return [
            'started_at' => ['nullable', 'required_with:finished_at', 'date'],
            'finished_at' => ['nullable', 'after:started_at', 'date'],
        ];
    }
}