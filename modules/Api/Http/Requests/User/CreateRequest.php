<?php

namespace Modules\Api\Http\Requests\User;

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
            $this->accessToken->hasPermissionTo('users.create');
    }

    /**
     * @return \string[][]
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'phone:AUTO', 'unique:users,phone'],
            'password' => ['required', 'min:8'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['required', 'integer'],
        ];
    }
}