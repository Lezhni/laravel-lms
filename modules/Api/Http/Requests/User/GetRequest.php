<?php

namespace Modules\Api\Http\Requests\User;

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
            $this->accessToken->hasPermissionTo('users.list');
    }
}