<?php

namespace Modules\Learning\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class QuizRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'results' => ['required', 'array'],
            'results.*.question' => ['required', 'exists:quizzes_questions,id'],
            'results.*.answers' => ['required', function ($attribute, $value, $fail) {
                if (!is_array($value) && !is_integer($value)) {
                    return $fail('Ответ должен быть идентификатором или массивом идентификаторов');
                }
            }],
        ];
    }
}
