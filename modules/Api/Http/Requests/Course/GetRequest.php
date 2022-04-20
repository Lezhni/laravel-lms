<?php

namespace Modules\Api\Http\Requests\Course;

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
            $this->accessToken->hasPermissionTo('courses.list') &&
            $this->accessToken->hasPermissionTo('users.list');
    }
}