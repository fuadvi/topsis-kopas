<?php

namespace App\Http\Requests;

use App\Http\Traits\ErrorForm;
use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    use ErrorForm;
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
            'name' => ['required','string'],
            'question_title_id' => ['required','integer','exists:question_titles,id'],
            'criteria_id' => ['required','integer','exists:criterias,id'],
            'type' => ['required','boolean'],
            'choices' => ['required','array'],
            'choices.*.name' => ['required','string'],
            'choices.*.point' => ['required','integer'],
            'choices.*.type' => ['required','boolean'],
        ];
    }
}
