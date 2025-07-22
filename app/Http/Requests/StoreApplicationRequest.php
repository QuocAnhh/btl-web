<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
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
            'aspirations' => ['required', 'array', 'min:1'],
            'aspirations.*.major_id' => ['required', 'integer', 'exists:majors,id'],
            'aspirations.*.priority' => ['required', 'integer', 'min:1'],
            'documents' => ['required', 'array', 'min:1'],
            'documents.hoc_ba' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:5120'], // 5MB max
            'documents.cccd' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
            'documents.bang_tot_nghiep' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
        ];
    }
}
