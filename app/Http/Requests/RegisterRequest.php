<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'nama' => ['required','string'],
            'email' => ['required','email'],
            'password' => ['required','string', 'confirmed', Password::min(8)],
            'asal_sekolah' => ['required','string'],
            'nis' => ['required','string'],
            'class' => ['required','string'],
            'jurusan_smk_id' => ['required','integer','exists:jurusan_smk,id']
        ];
    }
}
