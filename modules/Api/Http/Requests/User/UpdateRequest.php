<?php

namespace Modules\Api\Http\Requests\User;

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
            $this->accessToken->hasPermissionTo('users.update');
    }

    /**
     * @return \string[][]
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'phone:AUTO', 'unique:users,phone'],
            'password' => ['nullable', 'min:8'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['required', 'integer'],
        ];
    }
}