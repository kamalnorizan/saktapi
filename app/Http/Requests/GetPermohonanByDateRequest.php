<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetPermohonanByDateRequest extends FormRequest
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
            'date' => 'required|date|date_format:Y-m-d',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'date.required' => 'Date is required',
            'date.date' => 'Date must be a valid date',
            'date.date_format' => 'Date must be in Y-m-d format (e.g., 2025-10-29)',
        ];
    }
}
