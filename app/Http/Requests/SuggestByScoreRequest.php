<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SuggestByScoreRequest extends FormRequest
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
            'admission_method' => ['required', 'string', Rule::in(['exam_score', 'transcript_score'])],
            'scores' => ['required', 'array'],
            'scores.*.subject_name' => ['required', 'string'],
            'scores.*.score' => ['required', 'numeric', 'min:0', 'max:10'],
        ];
    }
}
