<?php

namespace Modules\Learning\Http\Requests\Lesson\Homework;

use Illuminate\Foundation\Http\FormRequest;

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
            'message' => ['nullable', 'string'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => [
                'bail',
                'required',
                'file',
                'min:1',
                'max:15360',
                'mimes:jpg,png,bmp,webp,svg,pdf,zip,rar,doc,docx,xls,xlsx,mp4',
            ]
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'message' => '"Сообщение"',
            'attachments.0' => '"Прикрепленный файл"',
        ];
    }
}
