<?php

namespace CreaceptLms\HomeworkChat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class ChatMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'grade_id' => ['required', 'exists:lessons_homeworks_grades,id'],
            'message' => ['required', 'string'],
        ];
    }
}