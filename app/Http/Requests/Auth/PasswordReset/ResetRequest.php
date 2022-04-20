<?php

namespace App\Http\Requests\Auth\PasswordReset;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class ResetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => ['required', 'exists:password_resets,token'],
            'password' => ['required', 'min:8', 'max:255', 'confirmed'],
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
            'token.exists' => 'Невалидный токен',
        ];
    }
}
