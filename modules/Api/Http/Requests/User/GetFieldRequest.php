<?php

namespace Modules\Api\Http\Requests\User;

use Modules\Api\Http\Requests\ApiRequest;

/**
 *
 */
class GetFieldRequest extends ApiRequest
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

    /**
     * @return \string[][]
     */
    public function rules(): array
    {
        return [
            'field' => ['required', 'string'],
            'value' => ['required'],
        ];
    }
}