<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data' => ['required','array'],
            'data.*.question_id' => ['required','integer','exists:questions,id'],
            'data.*.user_id' => ['required','integer','exists:users,id'],
            'data.*.criteria_id' => ['nullable','integer','exists:criterias,id'],
            'data.*.subject_id' => ['nullable','integer','exists:subjects,id'],
            'data.*.point' => ['required','integer'],
            'metode' => ['required','string','in:topsis,copras']
        ];
    }
}
