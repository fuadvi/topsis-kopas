<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BobotSubjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data' => ['required','array'],
            'data.*.subject_id' => ['required','integer','exists:App\Models\Subject,id'],
            'data.*.bobot' => ['required','integer']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
