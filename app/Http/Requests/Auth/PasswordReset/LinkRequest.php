<?php

namespace App\Http\Requests\Auth\PasswordReset;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class LinkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.exists' => 'Пользователя с таким email не существует',
        ];
    }
}
