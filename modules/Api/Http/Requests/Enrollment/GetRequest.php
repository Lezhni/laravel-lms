<?php

namespace Modules\Api\Http\Requests\Enrollment;

use Modules\Api\Http\Requests\ApiRequest;

/**
 *
 */
class GetRequest extends ApiRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return
            parent::authorize() &&
            $this->accessToken->hasPermissionTo('enrollments.list');
    }
}