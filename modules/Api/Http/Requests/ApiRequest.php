<?php

namespace Modules\Api\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Modules\Api\Models\AccessToken;

/**
 * Class ApiRequest
 * @package Modules\Api\Http\Requests
 */
class ApiRequest extends FormRequest
{
    /**
     * @var \Modules\Api\Models\AccessToken
     */
    protected AccessToken $accessToken;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        $accessKey = $this->get('access_token');
        $accessToken = AccessToken::published()->with('permissions')->where('key', $accessKey)->first();
        if (!$accessToken instanceof AccessToken) {
            return false;
        }

        $this->accessToken = $accessToken;
        return true;
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     */
    protected function failedAuthorization()
    {
        $response = new JsonResponse([
            'message' => 'Доступ к функции запрещен: невалидный токен или отсутствие прав доступа'
        ], 403);
        abort($response);
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'message' => 'Невалидные параметры запроса',
            'data' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }

    public function rules(): array
    {
        return [];
    }
}