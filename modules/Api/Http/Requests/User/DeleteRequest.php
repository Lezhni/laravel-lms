<?php

namespace Modules\Api\Http\Requests\User;

use Modules\Api\Http\Requests\ApiRequest;

/**
 *
 */
class DeleteRequest extends ApiRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return
            parent::authorize() &&
            $this->accessToken->hasPermissionTo('users.delete');
    }
}