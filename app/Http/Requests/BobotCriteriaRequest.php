<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BobotCriteriaRequest extends FormRequest
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
            'data.*.range' => ['required','integer'],
            'data.*.point' => ['required','integer'],
            'data.*.name' => ['required','string','exists:App\Models\SubCriteria,name'],
        ];
    }
}
