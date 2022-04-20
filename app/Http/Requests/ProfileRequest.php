<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $userId = $this->post('userId');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', "unique:users,email,{$userId}", 'max:255'],
            'phone' => ['nullable', 'phone:AUTO', "unique:users,phone,{$userId}", 'max:255'],
            'avatar' => ['nullable', 'file', 'mimes:jpg,svg,png,bmp,webp'],
            'password' => ['nullable', 'confirmed', 'min:8', 'max:255'],
            'extra_fields.country' => ['nullable', 'string'],
            'extra_fields.city' => ['nullable', 'string'],
            'extra_fields.sex' => ['nullable', Rule::in(User::SEX_LIST)],
        ];
    }
}
